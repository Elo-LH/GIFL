// import Masonry from 'masonry-layout'

// window.onload = () => {
//   const masonryGrid = document.querySelector('.masonry-grid')
//   console.log(masonryGrid)
//   const masonry = new Masonry(grid)
// }

document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  //Getting input
  // const inputSearch = document.querySelector('.input-search')
  // if (inputSearch) {
  //   let userInput = ''
  //   inputSearch.addEventListener('input', (e) => (userInput = e.target.value))
  // }

  //Getting search-result-wrapper
  const searchResultWrapper = document.querySelector('.search-result-wrapper')
  if (searchResultWrapper) {
    console.log(searchResultWrapper)
    const height = searchResultWrapper.offsetHeight
    console.log(height)
    console.log(searchResultWrapper)
    searchResultWrapper.style.height = height / 5
    console.log(searchResultWrapper)
  }
})
