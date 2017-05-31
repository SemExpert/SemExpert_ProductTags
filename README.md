# Product Tags

**Product Tags** adds a helper meant to be used in product list templates and product view page. The helper will 
evaluate the state of a given product and display the product tags accordingly.

Product tags markup and relevant conditions (when applicable) are fully customizable from the admin panel.

Currently implemented tags are:

* New product (when product is set as news using native "news from" and "news to" attributes)
* Product on sale (when final price is lower than list price)
* Free shipping (when price exceeds a customizable threshold. Not linked to cart promotions at the moment)