//initialize link to API

const api = 'http://gifl/index.php?route='

function fetchData(apiUrl) {
  fetch(apiUrl).then((response) => {
    console.log(response)
    if (response.ok) {
      //response.json().then(console.log)
      response.json().then((data) => {
        console.log('La requête a  réussi')
        console.log(data)
        return data
      })
    } else {
      // La requete a echoué
      console.log('La requête a échoué')
      console.log(response)
    }
  })
}

const getParameter = (key) => {
  // Address of the current window
  let address = window.location.search

  // Returns a URLSearchParams object instance
  let parameterList = new URLSearchParams(address)

  // Returning the respected value associated
  // with the provided key
  return parameterList.get(key)
}

document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  // Getting input
  const inputSearch = document.querySelector('.input-search')
  if (inputSearch) {
    let userInput = ''
    inputSearch.addEventListener('input', (e) => {
      userInput = e.target.value
      const searchResultDisplay = document.querySelector(
        '.js-search-result-display'
      )
      searchResultDisplay.textContent = ''
      fetch(api + 'get-hashtag-search-result&input=' + userInput).then(
        (response) => {
          console.log(response)
          if (response.ok) {
            //response.json().then(console.log)
            response.json().then((data) => {
              console.log('La requête a  réussi')
              console.log(data)
              data.forEach((gif) => {
                const collectionId = getParameter('collection')
                const gridItem = document.createElement('div')
                gridItem.classList.add('masonry-grid-item')
                const gridLink = document.createElement('a')
                gridLink.classList.add('masonry-grid-link')
                gridLink.href = `index.php?route=gif&gif=${gif.id}`
                const gifImg = document.createElement('img')
                gifImg.classList.add('masonry-grid-img')
                gifImg.alt = ''
                gifImg.src = gif.link
                gridLink.appendChild(gifImg)
                gridItem.appendChild(gridLink)
                if (
                  searchResultDisplay.classList.contains('js-collection-add')
                ) {
                  const gridAddLink = document.createElement('a')
                  gridAddLink.classList.add(
                    'link-button',
                    'very-small-button',
                    'yellow-button',
                    'masonry-grid-item-icon'
                  )
                  gridAddLink.href = `index.php?route=add-gif-to-collection&gif=${gif.id}&collection=${collectionId}`
                  gridAddLink.textContent = 'Add'
                  gridItem.appendChild(gridAddLink)
                }
                searchResultDisplay.appendChild(gridItem)

                //Load masonry displaay
                var elem = document.querySelector('.masonry-grid')
                var msnry = new Masonry(elem, {
                  // options
                  itemSelector: '.masonry-grid-item',

                  gutter: 5,
                })
              })
            })
          } else {
            // La requete a echoué
            console.log('La requête a échoué')
            console.log(response)
          }
        }
      )
    })
  }

  // Displaying GIF modale on click
  const gifModaleOverlay = document.querySelector('.gif-display-modale-overlay')
  if (gifModaleOverlay) {
    const gifModale = document.querySelector('.gif-display-modale')
    const gifItems = document.querySelectorAll('.js-gif-modale')
    console.log('query gifItems')
    gifItems.forEach((gifItem) => {
      gifItem.addEventListener('click', (e) => {
        console.log('event click modale')
        const gifImg = document.createElement('img')
        gifImg.src =
          'https://media1.tenor.com/m/qYQjQJ9WtZgAAAAC/sloth-baby.gif'
        gifModale.appendChild(gifImg)
        gifModaleOverlay.classList.toggle('modale-hidden')
        gifModale.classList.toggle('modale-hidden')
      })
    })
    const closeBtn = document.querySelector('.js-close-modale')
    closeBtn.addEventListener('click', () => {
      gifModale.classList.toggle('modale-hidden')
      gifModaleOverlay.classList.toggle('modale-hidden')
    })
  }
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
