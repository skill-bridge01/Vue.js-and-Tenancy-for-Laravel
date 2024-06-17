// Optional. This example returns data from response object
export const responseInterceptor = (response) => {

  if (response.errors) {
    return Promise.reject('Error')
  }
  return response
}
