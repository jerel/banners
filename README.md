The Banners module is a very flexible tool for displaying images or text on specific pages or urls of your site. The Banners module is _url driven_. This means that it uses the visitor's location to determine which banner set to display. For simple usage you will select pages from the "Select Pages" field and their url location will be determined for you. If you want more control simply click "Show Advanced Options" and create your own url pattern rules.

#### Using the Single Tag

In its simplest form you may display an image set using the following tag: `{{ banners:display }}` Usage: let's say that you have a page named "Find Us" and a page named "Contact Us" that both use the default page layout. If you wanted to display an image of your business on both pages you could do this:

1. Create a banner set and give it a name. Select both pages from the "Select Pages" field
2. Upload your image or images to the set
3. Place the `{{ banners:display debug="true" }}` tag in the Page Layout or in the theme layout file
4. Reload the page and the image or images will be displayed
5. When you are confident it is working properly remove the debug attribute. The cache will now be refreshed only when you add or edit banners.

You may pass attributes to the banners plugin to set the image width, debug mode, limit, etc. Below is a single tag with all possible attributes:

    {{ banners:display 
        debug="true" // add this attribute during development to turn off caching
        slug="header" // if multiple sets are assigned a location you can select one by slug
        width="800"  // set the image with. Only used in single tag mode
        height="250" // set the image height. Only used in single tag mode
        mode="fill"  // set the image crop mode. Only used in single tag mode.
        limit="5"  // limit banner sets returned if multiple sets are assigned to a location
        image-limit="10" // the maximum number of images to be returned per set
        order-by="name" // order the sets by database column
        order-dir="desc" // the direction to sort banner sets by: asc or desc
        image-order-by="sort" // order images by database column
        image-order-dir="desc" // the direction to sort images by: asc or desc
    }}

#### Using the Double Tag

You may use the double tag to do many more things not possible with the single tag. Since images are optional you may even want to use a banner set to display nothing but text. This is very possible. This example would display the text from the banner set's wysiwyg editor:

    {{ banners:display }}
        {{ text }}
    {{ /banners:display }}`

The double tag provides more functionality than the single tag, however since you are manipulating the image url manually you must add the image width, height, and mode to the url instead of passing it as an attribute. Below is a double tag with all possible attributes and common data fields:

    {{ banners:display 
        debug="true" // add this attribute during development to turn off caching
        slug="header" // if multiple sets are assigned a location you can select one by slug
        limit="5"  // limit banner sets returned if multiple sets are assigned to a location
        image-limit="10" // the maximum number of images to be returned per set
        order-by="name" // order the sets by database column
        order-dir="desc" // the direction to sort banner sets by: asc or desc
        image-order-by="sort" // order images by database column
        image-order-dir="desc" // the direction to sort images by: asc or desc
    }}
    <h4>{{ name }}</h4>
    <div>{{ text }}</div>
    <img src="{{ url:site }}files/large/{{ filename }}/800/300/fit" alt="{{ description }}"/>
    {{ /banners:display }}


Or you can do special things with your images by using the double tag. For example:

    {{ banners:display }}
        <div class="slide-show">
            <img src="{{ url:site }}files/large/{{ filename }}/800/300" alt="{{ description }}"/>
        </div>
    {{ /banners:display }}

The images for the Banners module are stored in the "files" table so all fields in that table are available within the banners double tag. You will also notice that the folders and files are visible via the Files interface.

#### Defining URL Patterns

You may define url patterns using regular expressions. This makes it easy to display a banner set on certain types of urls without only choosing specific pages. Below are some simple examples using an asterisk to specify a wildcard. (for you regex professionals the asterisk is converted to (.*?) before the expression is evaluated)

	# the following line would display the banner set on the index page of the blog module
	blog
	# the following line would display the banner set on all of the blog module below the blog index page
	blog/*
	# this would display the banner set on all blog archives for the month of December in any year
	blog/*/12/*
	# Using raw regex: the following would display the set on any url with only numbers as the last segment
	(.*?)/[0-9]+
	# how about showing your handsome photo on every location that has your name in the url?
	(.*?)?your-name(.*?)?
