seajs.config({
  base: "/src/js/",
  alias: {
  	'webuploader':'libs/webuploader/0.1.6/webuploader.min.js'
  },
  map: [
    [ /^(.*\/module\/.*\.(?:css|js))(?:.*)$/i, '$1?20151215' ]
  ]
})