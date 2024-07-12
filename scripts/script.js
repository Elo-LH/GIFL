document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  //Getting input
  const inputSearch = document.querySelector('.input-search')
  let userInput = ''
  inputSearch.addEventListener('input', (e) => (userInput = e.target.value))
})
