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
    const authOptions = document.querySelector('.gif-card-auth-options')
    console.log('query gifItems')

    // close modale on click on overlaw
    window.onclick = function (event) {
      if (event.target.classList.contains('gif-display-modale-overlay')) {
        gifModale.classList.toggle('modale-hidden')
        gifModaleOverlay.classList.toggle('modale-hidden')
        authOptions.classList.toggle('modale-hidden')
      }
    }

    // Add event listener on each gif item
    gifItems.forEach((gifItem) => {
      gifItem.addEventListener('click', (e) => {
        console.log('event click modale')
        // If click on GIF

        //retrieve GIF id
        const gifIdName = e.target.id
        const gifId = gifIdName.slice(10)
        console.log(gifId)

        // generate report button
        authOptions.innerHTML = ''
        authOptions.classList.toggle('modale-hidden')
        const reportBtn = document.createElement('button')
        reportBtn.classList.add(
          'pink-button',
          'very-small-button',
          'gif-report-btn'
        )
        reportBtn.textContent = 'Report'
        reportBtn.addEventListener('click', () => {
          fetch(api + 'put-gif-reported&gif=' + gifId).then((response) => {
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
        })

        authOptions.appendChild(reportBtn)

        //Generate select
        const selectCollection = document.createElement('select')
        //fetch collections from user
        //for each collection generate an option with value = collection_id and collection name

        // Fetch gif infos from API
        fetch(api + 'get-gif-info&gif=' + gifId).then((response) => {
          console.log(response)
          if (response.ok) {
            //response.json().then(console.log)
            response.json().then((data) => {
              console.log('La requête a  réussi')
              console.log(data)
              // use fetched data to generate modale

              // add gif img
              const gifImg = document.querySelector('.js-gif-modale-img')
              gifImg.src = data[0].link
              const gifHashtags = document.querySelector(
                '.js-gif-modale-hashtags'
              )
              // add each hashtag link
              data.forEach((hashtag, i) => {
                if (i == 0) {
                  gifHashtags.innerHTML = ''
                } else {
                  const gifHashtag = document.createElement('a')
                  gifHashtag.textContent = `#${hashtag.name}`
                  gifHashtag.classList.add('gif-card-hashtag')
                  gifHashtag.href = `index.php?route=hashtag-page&hashtag=${hashtag.id}`
                  gifHashtags.appendChild(gifHashtag)
                }
              })
              // fill input with link to gif page
              const input = document.querySelector('.js-gif-modale-input')
              input.value = data[0].link
              //show modale
              gifModaleOverlay.classList.toggle('modale-hidden')
              gifModale.classList.toggle('modale-hidden')
            })
          } else {
            // La requete a echoué
            console.log('La requête a échoué')
            console.log(response)
          }
        })
      })

      //Add keyboard control to open modale (accessibility)
      gifItem.addEventListener('keydown', (event) => {
        if (event.code === 'Space' || event.code === 'Enter') {
          gifItem.click()
        }
      })
    })

    // Add event listener on close Button
    const closeBtn = document.querySelector('.js-close-modale')
    closeBtn.addEventListener('click', () => {
      gifModale.classList.toggle('modale-hidden')
      gifModaleOverlay.classList.toggle('modale-hidden')
      authOptions.classList.toggle('modale-hidden')
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

window.onload = () => {
  // const masonryGrid = document.querySelector('.masonry-grid')
  // console.log(masonryGrid)
  // const masonry = new Masonry(grid)

  var elem = document.querySelector('.masonry-grid')
  var msnry = new Masonry(elem, {
    // options
    itemSelector: '.masonry-grid-item',

    gutter: 5,
  })
}