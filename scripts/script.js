document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  //Getting input
  // const inputSearch = document.querySelector('.input-search')
  // if (inputSearch) {
  //   let userInput = ''
  //   inputSearch.addEventListener('input', (e) => (userInput = e.target.value))
  // }

  const toggleBurger = () => {
    console.log('toggle burger')
    const pageHeaderNav = document.querySelector('.page-header-nav')
    pageHeaderNav.classList.toggle('burger-display')
  }
  //Getting burger-menu
  const arrowPink = document.querySelector('.arrow-pink')
  if (arrowPink) {
    const arrowYellow = document.querySelector('.arrow-yellow')
    arrowPink.addEventListener('click', toggleBurger)
    arrowYellow.addEventListener('click', toggleBurger)
  }

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

  //upload drag and drop
  const dropContainer = document.getElementById('dropcontainer')
  if (dropContainer) {
    const fileInput = document.getElementById('image')

    dropContainer.addEventListener(
      'dragover',
      (e) => {
        e.preventDefault()
      },
      false
    )

    dropContainer.addEventListener('dragenter', () => {
      dropContainer.classList.add('drag-active')
    })

    dropContainer.addEventListener('dragleave', () => {
      dropContainer.classList.remove('drag-active')
    })

    dropContainer.addEventListener('drop', (e) => {
      e.preventDefault()
      dropContainer.classList.remove('drag-active')
      fileInput.files = e.dataTransfer.files
    })
  }
})
