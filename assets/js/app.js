import '../css/app.scss'

import './elements/RecapLiveElement'
import './elements/PlayButton.js'
import './elements/YoutubePlayer.js'
import './elements/Waves'
import './elements/Alert'
import '@grafikart/spinning-dots-element'
import Turbolinks from 'turbolinks'

import './modules/scrollreveal'

document.querySelector('#dark-toggle a').addEventListener('click', function (e) {
  e.preventDefault()
  e.stopPropagation()
  document.body.classList.toggle('dark')
})

Turbolinks.start()
