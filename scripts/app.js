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
