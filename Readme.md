##Version
Glickr 0.9

##License

	The MIT License

	Copyright (c) 2009 Ivan Ribas

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN


##Features

* Let others view your flickr photos in your own website.</li>
* View your photos organized in flickr collections or photosets as in flickr.</li>
* Cache collections and photosets for faster load times.</li>
* Use of ajax for responsive gallery loading.</li>


##Requirements

* Apache server on Linux
* mod_rewrite
* php5
* flickr username and api key
* Some photos in your flickr account

You will need permissions to make a directory writable, application settings and cache files will be stored in this directory.


If you have a [flickr account](http://www.flickr.com) account you will need an [api key](http://www.flickr.com/services/api/misc.api_keys.html). Some tools like <a href="http://idgettr.com/">idgettr.com</a> can also help you figure your user id.


##Install

* Unzip the gallery file and upload it to your web server
* Point your browser to your web server and insert the required data (user id, api key)</li>
* Enjoy!


Note that the first time you run Glickr it will connect to flickr to build the cache files. It can take a few seconds depending on how many collections and sets you have in flickr.


##Cache

For faster performance two cache files are used to keep all the information about your photosets and collections, but no images are stored in your web server.


If you change your photosets or collections you will need to reset the cache in order to get the changes in your gallery, you can do so visiting a special webpage. 

IE: [http://example.com/path-to-gallery/rebuildCache](http://example.com/path-to-gallery/rebuildCache) on your server.

You can visit this webpage everytime you make changes, or you can add it to a cronjob schedule and make the cache be refreshed as often as you want.

Rebuilding your cache can take some time depending on how many photosets and collections you have in flickr.

##Use

Glickr will first try to show your collections. If you don't have collections it will try with photosets, and finally, if no photosets are found in your flickr account it will show all your photos.


Click on a collection title to view all photosets in the collection. Click on a photoset title to see all photos in the photoset.

Photos are displayed as thumbnails, click on thumbnails to see a medium sized photo.

When browsing photos in a photoset you will see links for next and previous photos in the photoset. If browsing single photos you will also have **Next** and **Previous** links and a **Back** link to the photos index.

##Customize

Glickr's html is pretty simple, so most of the time you will only need to edit the included css file in css/style.css.

In case you really need to change the html template you can do so by editing the files in the view/ folder. The file layout.php is the main layout.


##Bugs?

Yeah, sure. Just let me know if you find one and I will do my best to fix it. [Report a bug on github.com](https://github.com/htmlgraphic/glickr/issues)


##Contact

Please get in touch via a [contact me via github.com](https://github.com/htmlgraphic/glickr/issues)
