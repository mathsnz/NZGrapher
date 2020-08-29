self.addEventListener('install', function(e) {
 e.waitUntil(
   caches.open('nzgrapherv=VERSION').then(function(cache) {
     return cache.addAll([
       './',
       './jquery-1.11.1.min.js',
       './jquery.csv.js',
       './regression.min.js',
       'https://fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Condensed',
	   './style.css?v=VERSION',
	   './js.js?v=VERSION',
	   './jsnew.js?v=VERSION',
	   './logob.png',
	   './loading.gif',
	   './logow.png',
	   './3dots.png',
	   './change%20log.php',
	   './getdata.php?dataset=http%3A%2F%2Flocalhost%2FNZGrapher%2Fgrapher%2Fdatasets%2FBabies.csv',
	   './unchecked.png',
	   './checked.png',
	   './about.php'
     ]);
   })
 );
});

self.addEventListener('activate', function(event) {
  // Claim any clients immediately, so that the page will be under SW control without reloading.
  event.waitUntil(self.clients.claim());
});


self.addEventListener('fetch', function(event) {
 console.log(event.request.url);
	
  event.respondWith(
    caches.open('nzgrapher').then(function(cache) {
		return cache.match(event.request).then(function (response) {
			if(response){
				console.log('from cache');
				return response;
			} else {
				return fetch(event.request.clone()).then(function(response) {
				  if(event.request.method != "POST" && console.log(event.request.url.substr(-4)!='sw.js')){
					console.log('added to cache');
					cache.put(event.request, response.clone()); 
				  }
				  console.log('online');
				  return response;
				}).catch(function(error){
					console.log('no connection');
					if(event.request.method === "POST"){
						console.log('is post');
						console.log(event.request);
						return event.request.formData().then(formData => {
							for(var pair of formData.entries()) {
							  var key = pair[0];
							  var value =  pair[1];
							  if(key=='imgBase64'){
								  return new Response('<img src="'+value+'" usemap="#graphmap">');
							  }
							}
						})
					}
					return new Response('Sorry, that does not work while you are offline. <a href="./">Reload NZGrapher</a>',{
					  headers: {'Content-Type': 'text/html'}
					});
				});
			}
		});
    })
  );
});