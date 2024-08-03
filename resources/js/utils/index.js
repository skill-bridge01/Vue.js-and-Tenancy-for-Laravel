
export const getDataLabelByQuery = (index) => {
  switch (index) {
    case 2: // day
      return [2, 4, 8, 10, 12, 14, 16, 18, 20, 22, 24];
    case 1: // month
      return [
        1, 2, 3, 4, 5, 6, 7,
        8, 9, 10, 11, 12, 13, 14,
        15, 16, 17, 18, 19, 20, 21,
        22, 23, 24, 25, 26, 27, 28,
        29, 30, 31
      ];
    case 0: // year
      return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
  }
}

export const getQueryByIndex = (index) => {
  switch (index) {
    case 0: // year
      return 'year';
    case 1: // month
      return 'month';
    case 2: // day
      return 'day';
  }
}

export const getServiceDataFromStatistics = (data = [], tabIndex) => {
  console.log(`tab index : ${tabIndex}`);
 const newData = data.map((d, i) => {
  let serviceName = ""
  let pieceName = ""
  let count = ""
  if (tabIndex === 0) {
    serviceName = d['year_service_data'] ? d['year_service_data']['services_title'] : null;
    pieceName = d['year_piece_data']? d['year_piece_data']['piece_title'] : null;
    count = d['number_of_piece'] ? parseInt(d['number_of_piece']) : 0;
  } else if (tabIndex === 1) {
    serviceName = d['month_service_data'] ? d['month_service_data']['services_title'] : null;
    pieceName = d['month_piece_data']? d['month_piece_data']['piece_title'] : null;
    count = d['number_of_piece'] ? parseInt(d['number_of_piece']) : 0;
  } else {
    serviceName = d['day_service_data'] ? d['day_service_data']['services_title'] : null;
    pieceName = d['day_piece_data']? d['day_piece_data']['piece_title'] : null;
    count = d['number_of_piece'] ? parseInt(d['number_of_piece']) : 0;
  }

  return {
    serviceName,
    pieceName,
    count
  };
 });
 return newData;
};

export const getTableOptionsForCustomers = (data = []) => {
  console.log('CustomerData',data);
  const newData = data.map((d, i) => {
    return {
      amount: parseInt(d['total']),
      ...d['customer_data'],
    }
  });
  console.log('NEWcumtomer');
  console.log(newData);
  return newData;
}

export const getTableOptionsForServices = (data1 = []) => {
  console.log('ServiceData',data1);
  const newData1 = data1.map((d, i) => {
    return {
      amount: parseInt(d['total']),
      service_name: d['service']
    }
  });
  console.log('NEWservice');
  console.log(newData1);
  return newData1;
}