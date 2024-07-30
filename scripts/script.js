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

document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  // Getting input
  const inputSearch = document.querySelector('.input-search')
  if (inputSearch) {
    let userInput = ''
    inputSearch.addEventListener('input', (e) => {
      userInput = e.target.value
      const searchResultDisplay = document.querySelector(
        '.search-result-display'
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
                const gifImg = document.createElement('img')
                gifImg.src = gif.link
                searchResultDisplay.appendChild(gifImg)
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
