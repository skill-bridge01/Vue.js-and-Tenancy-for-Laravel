<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;
use App\Transformers\StatsticesTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Throwable;

class GetStatstices extends Api
{
    
    // public static function routes($router)
    // {
    //     $router->post('api/get-statstices',static::class);
    // }

   

    public function handle($query=null)
    {
        try{
            $query = $query?:request();
            $query_data = data_get($query,'query', null);
            $customer_data = Invoice::selectRaw('customer_id')
                                    ->groupBy('customer_id')
                                    ->get();
            if($query_data == "month"){
                $days_of_month = date('t',strtotime('today'));
                $month_total_array = array();

                for ($j = 1; $j <= $days_of_month; $j++) {
                    $yearData=date('Y');
                    $monthData=date('m');
                    $fromDate=date_format(date_create($yearData.'-'.$monthData.'-'.$j),'Y-m-d');
                    $service_piece_invoice_data = Service_piece_invoices::whereDate('created_at', $fromDate)
                                                                        ->get();
                    $total_price=0;
                    foreach($service_piece_invoice_data as $item){
                        $service_piece_data = Service_piece::where('id',$item->service_piece_id)->first();
                        $total_price += $item->number_of_piece * $service_piece_data['price'];
                    }       
                    $month_total_array[$j] = $total_price;
                }

                $month_statistic_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                                ->groupBy('service_piece_id')
                                                                ->whereYear('created_at', date('Y'))
                                                                ->whereMonth('created_at', date('m'))
                                                                ->get();
                $month_statistic_array = array();

                foreach ($month_statistic_data as $item) {
                    $sub_total = array();
                    for($j = 1; $j <= $days_of_month; $j++) {
                        $yearData=date('Y');
                        $monthData=date('m');
                        $fromDate=date_format(date_create($yearData.'-'.$monthData.'-'.$j),'Y-m-d');
                        $month_service_piece_invoice_data = Service_piece_invoices::whereDate('created_at', $fromDate)
                                                                                ->where('service_piece_id',$item->service_piece_id)->get();
                        $total_price = 0;

                        foreach ($month_service_piece_invoice_data as $item_data) {
                            $service_piece_data = Service_piece::where('id',$item_data->service_piece_id)->first();
                            $total_price += $item_data->number_of_piece*$service_piece_data['price'];
                        }
                        array_push($sub_total, $total_price);
                    }

                    $service_piece_data = Service_piece::where('id',$item->service_piece_id)->first();
                    $piece_data = Piece::where('id',$service_piece_data['piece_id'])->first();
                    $service_data = Service::where('id',$service_piece_data['service_id'])->first();
                    $item_array = array(
                        'service_piece_id'=>$item->service_piece_id,
                        'total'=>$item->number_of_piece*$service_piece_data['price'],
                        'number_of_piece'=>$item->number_of_piece,
                        'sub_total'=>$sub_total,
                        'service_name'=>$service_data['services_title'],
                        'piece_name'=>$piece_data['piece_title']);    
                    array_push($month_statistic_array,$item_array);

                }

                array_multisort(array_column($month_statistic_array, 'total'), SORT_DESC, $month_statistic_array);
                array_splice($month_statistic_array, 3);
                $month_statistic_obj_data = json_decode(json_encode($month_statistic_array));

                $month_customer_array = array();

                foreach($customer_data as $customer_item){
                    $customer_detail_data = Customer::where('id',$customer_item->customer_id)->first();
                    $invoice_data = Invoice::where('customer_id',$customer_item->customer_id)->get();
                    $month_total = 0;

                    foreach ($invoice_data as $invoice_item) {
                        $service_piece_invoice_data_arr = Service_piece_invoices::whereMonth('created_at', date('m'))
                                                                            ->where('invoice_id',$invoice_item->id)
                                                                            ->get();
                        foreach ($service_piece_invoice_data_arr as $service_piece_invoice_data) {
                            if ($service_piece_invoice_data) {
                                $service_piece_data = Service_piece::where('id',$service_piece_invoice_data['service_piece_id'])->first();
                                $month_total += $service_piece_invoice_data['number_of_piece']*$service_piece_data['price'];
                            }
                        }                       
    
                    }
                    array_push(
                        $month_customer_array,
                        array(
                            'customer_data' => $customer_detail_data,
                            'total'=>$month_total
                        )
                    );
                }
                array_multisort(array_column($month_customer_array, 'total'), SORT_DESC, $month_customer_array);
                array_splice($month_customer_array, 5);

                $month_cloth_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                            ->groupBy('service_piece_id')
                                                            ->whereMonth('created_at', date('m'))
                                                            ->get(); 
                $month_cloth_array = array();

                foreach ($month_cloth_data as $month_cloth_item) {
                $month_service_piece_data = Service_piece::where('id',$month_cloth_item['service_piece_id'])->first();
                $month_piece_data = Piece::where('id',$month_service_piece_data['piece_id'])->first();
                $month_service_data = Service::where('id',$month_service_piece_data['service_id'])->first();

                array_push(
                    $month_cloth_array,
                    array(
                        'number_of_piece' => $month_cloth_item['number_of_piece'], 
                        'month_piece_data' => $month_piece_data, 
                        'month_service_data' => $month_service_data
                    )
                );

                }        
                array_multisort(
                    array_column($month_cloth_array, 'number_of_piece'), 
                    SORT_DESC,
                    $month_cloth_array
                );
                array_splice($month_cloth_array, 5);     

                $cards = array(
                    'total_sales' => $month_total_array, 
                    'most_services' => $month_statistic_array,
                    'most_purchased_customers'=>$month_customer_array,
                    'most_serviced_items'=>$month_cloth_array,
                    'query' => 'month',
                );
                return $cards ;

            } else if ($query_data == "year"){
                $year_total_array = array();
                $yearData=date('Y');
                for ($k = 1; $k <= 12; $k++) {
                    $n = $k-1;
                    $from_month_data=date_format(date_create($yearData.'-'.$k.'-'.'1'),'Y-m-d');
                    $last_day=date_format(date_create($yearData.'-12-31'),'Y-m-d');
                    if($k==12){
                        $service_piece_invoice_data = Service_piece_invoices::whereDate('created_at','>=', $from_month_data)
                                                                        ->whereDate('created_at','<=', $last_day)->get();
                    }else{
                        $to_month_data=date_format(date_create($yearData.'-'.($k+1).'-'.'1'),'Y-m-d');
                    $service_piece_invoice_data = Service_piece_invoices::whereDate('created_at','>=', $from_month_data)
                                                                        ->whereDate('created_at','<', $to_month_data)->get();
                    }

                    $total_price=0;

                    foreach ($service_piece_invoice_data as $item) {
                        $service_piece_data=Service_piece::where('id',$item->service_piece_id)->first();
                        $total_price+=$item->number_of_piece*$service_piece_data['price'];
                    }  
                    $year_total_array[$n]=$total_price;                 

                }

                $year_statistic_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                            ->groupBy('service_piece_id')
                                                            ->whereYear('created_at', date('Y'))
                                                            ->get();
                $year_statistic_array = array();

                foreach ($year_statistic_data as $item) {
                $sub_total = array();
                for ($k=1; $k<=12; $k++) {
                    $from_month_data=date_format(date_create($yearData.'-'.$k.'-'.'1'),'Y-m-d');
                    $last_day=date_format(date_create($yearData.'-12-31'),'Y-m-d');
                    if($k==12){
                        $year_service_piece_invoice_data = Service_piece_invoices::whereDate('created_at','>=', $from_month_data)
                                                                                    ->whereDate('created_at','<=', $last_day)
                                                                                    ->where('service_piece_id', $item->service_piece_id)->get();
                    }else{
                        $to_month_data=date_format(date_create($yearData.'-'.($k+1).'-'.'1'),'Y-m-d');
                        $year_service_piece_invoice_data = Service_piece_invoices::whereDate('created_at','>=', $from_month_data)
                                                                                    ->whereDate('created_at','<', $to_month_data)
                                                                                    ->where('service_piece_id', $item->service_piece_id)->get();
                    }
                    $total_price = 0;
                    foreach ($year_service_piece_invoice_data as $item_data) {
                        $service_piece_data = Service_piece::where('id',$item_data->service_piece_id)->first();
                        $total_price += $item_data->number_of_piece * $service_piece_data['price'];
                    }
                    array_push($sub_total, $total_price);
                }
                $service_piece_data = Service_piece::where('id', $item->service_piece_id)->first();
                $piece_data = Piece::where('id',$service_piece_data['piece_id'])->first();
                $service_data = Service::where('id',$service_piece_data['service_id'])->first();
                $item_array = array(
                    'service_piece_id'=>$item->service_piece_id,
                    'total'=>$item->number_of_piece*$service_piece_data['price'],
                    'number_of_piece'=>$item->number_of_piece,
                    'sub_total'=>$sub_total,
                    'service_name'=>$service_data['services_title'],
                    'piece_name'=>$piece_data['piece_title']);  
                array_push($year_statistic_array, $item_array);
                }
                array_multisort(array_column($year_statistic_array, 'total'), SORT_DESC, $year_statistic_array);
                array_splice($year_statistic_array, 3);
                $year_statistic_obj_data = json_decode(json_encode($year_statistic_array));

                $year_customer_array=array();

                foreach ($customer_data as $customer_item) {
                    $customer_detail_data = Customer::where('id', $customer_item->customer_id)->first();
                    $invoice_data = Invoice::where('customer_id', $customer_item->customer_id)->get();
                    $year_total = 0;
                    foreach ($invoice_data as $invoice_item) {
                        $service_piece_invoice_data_arr = Service_piece_invoices::whereYear('created_at', date('Y'))
                                                                            ->where('invoice_id',$invoice_item->id)->get();
                            // dd($service_piece_invoice_data_arr);

                        foreach ($service_piece_invoice_data_arr as $service_piece_invoice_data) {
                            if ($service_piece_invoice_data) {
                                $service_piece_data = Service_piece::where('id', $service_piece_invoice_data['service_piece_id'])->first();
                                $year_total += $service_piece_invoice_data['number_of_piece'] * $service_piece_data['price'];
                            }    
                        }
                        
                    }
                    array_push(
                        $year_customer_array,
                        array(
                            'customer_data' => $customer_detail_data,
                            'total' => $year_total
                        )
                    );
                }
                // dd($year_customer_array);
                array_multisort(array_column($year_customer_array, 'total'), SORT_DESC, $year_customer_array);
                array_splice($year_customer_array, 5);

                $year_cloth_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                            ->groupBy('service_piece_id')
                                                            ->whereYear('created_at', date('Y'))
                                                            ->get(); 
                $year_cloth_array=array();

                foreach ($year_cloth_data as $year_cloth_item) {
                    $year_service_piece_data = Service_piece::where('id', $year_cloth_item['service_piece_id'])->first();
                    $year_piece_data=Piece::where('id', $year_service_piece_data['piece_id'])->first();
                    $year_service_data=Service::where('id', $year_service_piece_data['service_id'])->first();

                    array_push(
                        $year_cloth_array,
                        array(
                            'number_of_piece' => $year_cloth_item['number_of_piece'],
                            'year_piece_data' => $year_piece_data,
                            'year_service_data' => $year_service_data
                        )
                    );

                }        
                array_multisort(array_column($year_cloth_array, 'number_of_piece'), SORT_DESC, $year_cloth_array);
                array_splice($year_cloth_array, 5);  
                
                $cards = array(
                    'total_sales' => $year_total_array,
                    'most_services' => $year_statistic_array,
                    'most_purchased_customers' => $year_customer_array,
                    'most_serviced_items' => $year_cloth_array,
                    'query' => 'year',
                );
                return $cards ;
            } else {
                $today_total_array = array();
                for($i = 0; $i <= 22; $i = $i + 2){
                    $service_piece_invoice_data = Service_piece_invoices::whereDate('created_at', date('Y-m-d'))
                                                                        ->whereTime('created_at','>=', date($i.':00:00'))
                                                                        ->whereTime('created_at','<=', date(($i+2).':00:00'))->get();
                    $total_price = 0;
                    foreach ($service_piece_invoice_data as $item) {
                        $service_piece_data = Service_piece::where('id',$item->service_piece_id)->first();
                        $total_price += $item->number_of_piece * $service_piece_data['price'];
                    }
                    $today_total_array[$i] = $total_price;
                }
                $today_statistic_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                                ->groupBy('service_piece_id')
                                                                ->whereDate('created_at', date('Y-m-d'))
                                                                ->get();
                $statistic_array = array();
                foreach ($today_statistic_data as $item) {
                    $sub_total=array();
                    for ($i=0; $i<=22; $i=$i+2) {
                        $today_service_piece_invoice_data = Service_piece_invoices::whereDate('created_at', date('Y-m-d'))
                                                                            ->whereTime('created_at','>=', date($i.':00:00'))
                                                                            ->whereTime('created_at','<=', date(($i+2).':00:00'))
                                                                            ->where('service_piece_id', $item->service_piece_id)->get();
                        $total_price = 0;

                        foreach ($today_service_piece_invoice_data as $item_data) {
                            $service_piece_data = Service_piece::where('id',$item_data->service_piece_id)->first();
                            $total_price += $item_data->number_of_piece * $service_piece_data['price'];
                        }
                        array_push($sub_total, $total_price);
                    }
                    $service_piece_data = Service_piece::where('id', $item->service_piece_id)->first();
                    $piece_data = Piece::where('id',$service_piece_data['piece_id'])->first();
                    $service_data = Service::where('id',$service_piece_data['service_id'])->first();
                    $item_array = array(
                        'service_piece_id'=>$item->service_piece_id,
                        'total'=>$item->number_of_piece*$service_piece_data['price'],
                        'number_of_piece'=>$item->number_of_piece,
                        'sub_total'=>$sub_total,
                        'service_name'=>$service_data['services_title'],
                        'piece_name'=>$piece_data['piece_title']);   
                    array_push($statistic_array, $item_array);
                }

                array_multisort(array_column($statistic_array, 'total'), SORT_DESC, $statistic_array);
                array_splice($statistic_array, 3);
                $today_statistic_obj_data = json_decode(json_encode($statistic_array));

                $today_customer_array = array();
                
                foreach ($customer_data as $customer_item) {
                    $customer_detail_data = Customer::where('id', $customer_item->customer_id)->first();
                    $invoice_data = Invoice::where('customer_id', $customer_item->customer_id)->get();
                    $today_total = 0;
                    foreach ($invoice_data as $invoice_item) {
                        $service_piece_invoice_data_arr = Service_piece_invoices::whereDate('created_at', date('Y-m-d'))
                                                                            ->where('invoice_id', $invoice_item->id)->get();
                        
                        foreach ($service_piece_invoice_data_arr as $service_piece_invoice_data) {
                            if ($service_piece_invoice_data) {
                                $service_piece_data = Service_piece::where('id', $service_piece_invoice_data['service_piece_id'])->first();
                                $today_total += $service_piece_invoice_data['number_of_piece'] * $service_piece_data['price'];
                            }
                        }
                    }
                    array_push(
                        $today_customer_array,
                        array('customer_data' => $customer_detail_data,'total' => $today_total)
                    );
                }
                array_multisort(array_column($today_customer_array, 'total'), SORT_DESC, $today_customer_array);
                array_splice($today_customer_array, 5);

                $today_cloth_data = Service_piece_invoices::selectRaw('service_piece_id, SUM(number_of_piece) AS number_of_piece')
                                                            ->groupBy('service_piece_id')
                                                            ->whereDate('created_at', date('Y-m-d'))
                                                            ->get(); 
                $today_cloth_array = array();
                foreach ($today_cloth_data as $today_cloth_item) {
                    $today_service_piece_data = Service_piece::where('id', $today_cloth_item['service_piece_id'])->first();
                    $today_piece_data = Piece::where('id', $today_service_piece_data['piece_id'])->first();
                    $today_service_data = Service::where('id', $today_service_piece_data['service_id'])->first();
                    array_push(
                        $today_cloth_array,
                        array(
                            'number_of_piece' => $today_cloth_item['number_of_piece'],
                            'today_piece_data' => $today_piece_data,
                            'today_service_data' => $today_service_data
                        )
                    );
                }        
                array_multisort(array_column($today_cloth_array, 'number_of_piece'), SORT_DESC, $today_cloth_array);
                array_splice($today_cloth_array, 5); 

                $cards = array(
                    'total_sales' => $today_total_array,
                    'most_services' => $statistic_array,
                    'most_purchased_customers' => $today_customer_array,
                    'most_serviced_items' => $today_cloth_array,
                    'query' => 'day',
                );
                return $cards ;
            }
        }
        catch(Throwable $ex)
        {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        
        }

    }
 
    public function jsonResponse($invoices)
    {
    //  return ["invoices"=>$invoices,"status"=>true];
        return ["cards"=>new StatsticesTransformer($invoices),"status"=>true];
    
    }
}
