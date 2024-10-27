//initialize link to API

//local API
// const api = 'http://gifl/index.php?route='
// const baseUrl = 'http://gifl/'
//planetHoster API
const api = 'https://eloise-lh.go.yj.fr/index.php?route='
const baseUrl = 'https://eloise-lh.go.yj.fr/'

var consoleHolder = console
function debug(bool) {
  if (!bool) {
    console = {}
    Object.keys(consoleHolder).forEach(function (key) {
      console[key] = function () {}
    })
  } else {
    console = consoleHolder
  }
}
// Attach the debug function to the global window object
window.debug = debug
// Call the debug function to disable console logs
debug(false)

function fetchData(apiUrl) {
  fetch(apiUrl).then((response) => {
    console.log(response)
    if (response.ok) {
      //response.json().then(console.log)
      response.json().then((data) => {
        console.log('Request succeeded')
        console.log(data)
        return data
      })
    } else {
      // Request failed
      console.error('Request failed')
      console.error(response)
    }
  })
}

function copyAdress() {
  console.log('copyAdress')
  // Copy the text inside the text field
  const inputSearch = document.querySelector('.input-search')
  const link = inputSearch.value
  console.log(link)
  if (navigator.clipboard) {
    navigator.clipboard.writeText(link)
  } else {
    const input = document.createElement('textarea')
    input.value = link
    document.body.appendChild(input)
    input.select()
    document.execCommand('copy')
    document.body.removeChild(input)
  }
  const copyMessage = document.querySelector('.js-copy-message')

  // Alert the copied text
  console.log(copyMessage)
  copyMessage.style.display = 'inline'
  setTimeout(() => {
    copyMessage.style.display = 'none'
  }, 2000)
}
function copyAdressGif() {
  console.log('copyAdressGif')
  // Copy the text inside the text field
  const inputSearch = document.querySelector('.js-input-search-gif')
  const link = inputSearch.value
  console.log(link)
  if (navigator.clipboard) {
    navigator.clipboard.writeText(link)
  } else {
    const input = document.createElement('textarea')
    input.value = link
    document.body.appendChild(input)
    input.select()
    document.execCommand('copy')
    document.body.removeChild(input)
  }
  const copyMessage = document.querySelector('.js-copy-message-gif')

  // Alert the copied text
  console.log(copyMessage)
  copyMessage.style.display = 'inline'
  setTimeout(() => {
    copyMessage.style.display = 'none'
  }, 2000)
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

const updateTheme = (theme) => {
  console.log('enter updateTheme')
  // Get the root element
  var r = document.querySelector(':root')
  if (theme === 'blackOnWhite') {
    // Update the value of color variables in css to chosen theme
    r.style.setProperty('--color-neon-pink', '#000000')
    r.style.setProperty('--color-neon-yellow', '#000000')
    r.style.setProperty('--color-dark-yellow', '#a8a8a8')
    r.style.setProperty('--color-dark-blue', '#ffffff')
    r.style.setProperty('--color-dark-purple', '#ffffff')
    r.style.setProperty('--color-aubergine', '#a8a8a8')
    r.style.setProperty('--color-whitey', '#000000')
    return
  }
  if (theme === 'whiteOnBlack') {
    // Update the value of color variables in css to chosen theme
    r.style.setProperty('--color-neon-pink', '#ffffff')
    r.style.setProperty('--color-neon-yellow', '#ffffff')
    r.style.setProperty('--color-dark-yellow', '#545454')
    r.style.setProperty('--color-dark-blue', '#000000')
    r.style.setProperty('--color-dark-purple', '#000000')
    r.style.setProperty('--color-aubergine', '#545454')
    r.style.setProperty('--color-whitey', '#ffffff')
    return
  }
}

const resetTheme = () => {
  // Get the root element
  var r = document.querySelector(':root')
  // Update the value of color variables in css to chosen theme
  r.style.setProperty('--color-neon-pink', '#D108FF')
  r.style.setProperty('--color-neon-yellow', '#FFBA08')
  r.style.setProperty('--color-dark-yellow', '#FAA307')
  r.style.setProperty('--color-dark-blue', '#110400')
  r.style.setProperty('--color-dark-purple', '#170327')
  r.style.setProperty('--color-aubergine', '#70008A')
  r.style.setProperty('--color-whitey', '#FFF1CD')
  return
}

const updateThemeCookie = () => {
  //Getting the theme inputs
  const selectedTheme = document.querySelector(
    'input[type = radio]:checked'
  ).value
  // set selected Theme as theme cookie
  if (
    selectedTheme === 'blackOnWhite' ||
    selectedTheme === 'whiteOnBlack' ||
    selectedTheme === 'default'
  ) {
    setCookie('theme', selectedTheme, 360)
  }

  // Updating theme with new cookie
  if (document.cookie.theme != 'default') {
    updateTheme(document.cookie.theme)
  } else {
    resetTheme()
  }
  // Refresh the page
  location.reload()
}

function getCookie(name) {
  var re = new RegExp(name + '=([^;]+)')
  var value = re.exec(document.cookie)
  return value != null ? unescape(value[1]) : null
}

function setCookie(name, value, expDays) {
  let date = new Date()
  date.setTime(date.getTime() + expDays * 24 * 60 * 60 * 1000)
  const expires = 'expires=' + date.toUTCString()
  document.cookie = name + '=' + value + '; ' + expires + '; path=/'
}

/* ==========================================================================
DOM CONTENT LOADED
========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  console.log('script loaded')

  console.log(document.cookie)
  const themeCookie = getCookie('theme')

  // Getting cookie for color theme
  if (themeCookie) {
    updateTheme(themeCookie)
  } else {
    resetTheme()
  }

  //Getting select theme button
  const updateThemeBtn = document.querySelector('.js-theme-update')
  if (updateThemeBtn) {
    console.log('add update theme event')
    updateThemeBtn.addEventListener('click', updateThemeCookie)
  }

  // Getting copy link button
  const copyBtn = document.querySelector('.js-copy-btn')
  if (copyBtn) {
    console.log('add copy adress event')
    copyBtn.addEventListener('click', copyAdress)
  }
  // Getting copy link button for gif modale
  const copyBtnGif = document.querySelector('.js-copy-btn-gif')
  if (copyBtnGif) {
    console.log('add copy adress event for gif')
    copyBtnGif.addEventListener('click', copyAdressGif)
  }

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
      fetch(api + 'get-hashtag-search-result&input=' + userInput)
        .then((response) => {
          console.log(response)
          if (response.ok) {
            //response.json().then(console.log)
            response
              .json()
              .then((data) => {
                console.log('Request succedeed')
                console.log(data)
                if (data) {
                  data.forEach((gif) => {
                    const collectionId = getParameter('collection')
                    //masonry-grid-item
                    const gridItem = document.createElement('div')
                    gridItem.classList.add('masonry-grid-item')
                    //masonry span for gif modale
                    const spanGIF = document.createElement('span')
                    spanGIF.classList.add('masonry-grid-link', 'js-gif-modale')
                    spanGIF.id = `js-gif-id-${gif.id}`
                    spanGIF.position = 'relative'
                    //masonry gif img
                    const gifImg = document.createElement('img')
                    gifImg.classList.add('masonry-grid-img')
                    gifImg.alt = ''
                    gifImg.src = gif.link
                    gifImg.id = `js-gif-id-${gif.id}`

                    //appending all elements
                    spanGIF.appendChild(gifImg)
                    gridItem.appendChild(spanGIF)

                    if (
                      searchResultDisplay.classList.contains(
                        'js-collection-add'
                      )
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
                      spanGIF.appendChild(gridAddLink)
                    }
                    searchResultDisplay.appendChild(gridItem)

                    if (gifImg.complete) {
                      //load masonry display
                      var elem = document.querySelector('.masonry-grid')
                      var msnry = new Masonry(elem, {
                        // options
                        itemSelector: '.masonry-grid-item',

                        gutter: 2,
                      })
                    }
                    // Add event listener on each gif item
                    const gifModale = document.querySelector(
                      '.gif-display-modale'
                    )
                    const gifItems = document.querySelectorAll('.js-gif-modale')
                    const authOptions = document.querySelector(
                      '.gif-card-auth-options'
                    )
                    gridItem.addEventListener('click', (e) => {
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
                        fetch(api + 'put-gif-reported&gif=' + gifId).then(
                          (response) => {
                            console.log(response)
                            if (response.ok) {
                              //response.json().then(console.log)
                              response.json().then((data) => {
                                console.log('Request succedeed')
                                console.log(data)
                                return data
                              })
                            } else {
                              // Request failed
                              console.error('Request failed')
                              console.log(response)
                            }
                          }
                        )
                      })

                      authOptions.appendChild(reportBtn)

                      //Generate select
                      const selectCollection = document.createElement('select')
                      //fetch collections from user
                      //for each collection generate an option with value = collection_id and collection name

                      // Fetch gif infos from API
                      fetch(api + 'get-gif-info&gif=' + gifId).then(
                        (response) => {
                          console.log(response)
                          if (response.ok) {
                            //response.json().then(console.log)
                            response.json().then((data) => {
                              console.log('Request succedeed')
                              console.log(data)
                              // use fetched data to generate modale

                              // add gif img
                              const gifImg =
                                document.querySelector('.js-gif-modale-img')
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
                              const input = document.querySelector(
                                '.js-gif-modale-input'
                              )
                              if (!data[0].link.startsWith('http')) {
                                input.value = baseUrl
                              }
                              input.value += data[0].link
                              //show modale
                              gifModaleOverlay.classList.toggle('modale-hidden')
                              gifModale.classList.toggle('modale-hidden')

                              // Getting copy link button for gif modale
                              const copyBtnGif =
                                document.querySelector('.js-copy-btn-gif')
                              if (copyBtnGif) {
                                console.log('add copy adress event for gif')
                                copyBtnGif.addEventListener(
                                  'click',
                                  copyAdressGif
                                )
                              }

                              //getting download button for gif modale
                              const downloadBtn =
                                document.querySelector('.js-download-gif')
                              if (downloadBtn) {
                                console.log('add event listener to dwnld btn')
                                downloadBtn.addEventListener(
                                  'click',
                                  downloadGif,
                                  false
                                )

                                async function downloadGif() {
                                  console.log('enter download gif')
                                  //create new a element
                                  let a = document.createElement('a')
                                  // get image as blob
                                  let response = await fetch(data[0].link)
                                  let file = await response.blob()
                                  console.log(file)
                                  // use download attribute https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#Attributes
                                  a.download = data[0].id
                                  a.href = window.URL.createObjectURL(file)
                                  console.log(a.href)
                                  //store download url in javascript https://developer.mozilla.org/en-US/docs/Learn/HTML/Howto/Use_data_attributes#JavaScript_access
                                  a.dataset.downloadurl = [
                                    'application/octet-stream',
                                    a.download,
                                    a.href,
                                  ].join(':')
                                  //click on element to start download
                                  a.click()
                                  window.URL.revokeObjectURL(file)
                                  downloadBtn.removeEventListener(
                                    'click',
                                    downloadGif,
                                    false
                                  )
                                  console.log(
                                    'remove event listener to dwnld btn'
                                  )
                                }
                              }
                            })
                          } else {
                            // Request failed
                            console.error('Request failed')
                            console.log(response)
                          }
                        }
                      )
                    })
                    //Add keyboard control to open modale (accessibility)
                    gridItem.addEventListener('keydown', (event) => {
                      if (event.code === 'Space' || event.code === 'Enter') {
                        gifItem.click()
                      }
                    })
                  })
                } else {
                  // Request failed
                  console.error('Request empty')
                  console.log(response)
                }
              })
              .catch((e) => console.error(e))
          } else {
            // Request failed
            console.error('Request failed or empty')
            console.log(response)
          }
        })
        .catch((e) => console.error(e))
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
                console.log('Request succedeed')
                console.log(data)
                return data
              })
            } else {
              // Request failed
              console.error('Request failed')
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
              console.log('Request succedeed')
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
              if (!data[0].link.startsWith('http')) {
                input.value = baseUrl
              }
              input.value += data[0].link
              //show modale
              gifModaleOverlay.classList.toggle('modale-hidden')
              gifModale.classList.toggle('modale-hidden')

              // Getting copy link button for gif modale
              const copyBtnGif = document.querySelector('.js-copy-btn-gif')
              if (copyBtnGif) {
                console.log('add copy adress event for gif')
                copyBtnGif.addEventListener('click', copyAdressGif)
              }

              //getting download button for gif modale
              const downloadBtn = document.querySelector('.js-download-gif')
              if (downloadBtn) {
                console.log('add event listener to dwnld btn')
                downloadBtn.addEventListener('click', downloadGif, false)

                async function downloadGif() {
                  console.log('enter download gif')
                  //create new a element
                  let a = document.createElement('a')
                  // get image as blob
                  let response = await fetch(data[0].link)
                  let file = await response.blob()
                  console.log(file)
                  // use download attribute https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#Attributes
                  a.download = data[0].id
                  a.href = window.URL.createObjectURL(file)
                  console.log(a.href)
                  //store download url in javascript https://developer.mozilla.org/en-US/docs/Learn/HTML/Howto/Use_data_attributes#JavaScript_access
                  a.dataset.downloadurl = [
                    'application/octet-stream',
                    a.download,
                    a.href,
                  ].join(':')
                  //click on element to start download
                  a.click()
                  window.URL.revokeObjectURL(file)
                  downloadBtn.removeEventListener('click', downloadGif, false)
                  console.log('remove event listener to dwnld btn')
                }
              }
            })
          } else {
            // Request failed
            console.error('Request failed')
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
  window.onload = () => {
    // const masonryGrid = document.querySelector('.masonry-grid')
    // console.log(masonryGrid)
    // const masonry = new Masonry(grid)

    var elem = document.querySelector('.masonry-grid')
    var msnry = new Masonry(elem, {
      // options
      itemSelector: '.masonry-grid-item',

      gutter: 2,
    })
  }
})
