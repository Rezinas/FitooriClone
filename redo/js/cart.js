var cart = {
	items : [],
	itemsTotal: 0,
	shipping: [],

	formatCurrency: function (total) {
	    var neg = false;
	    if(total < 0) {
	        neg = true;
	        total = Math.abs(total);
	    }
	    return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
	},
	getCartTotalItems: function() {
		return this.items.length;
	},
	getCartTotalPrice: function() {
		return this.itemsTotal;
	},
	getCart: function(){
		//gets the current cart object from server's session.
		var self = this;
		  $.getJSON( "php/ajax.php?getCart", function(json){
		      	 self.items=json.productids;
		      	 self.itemsTotal=parseFloat(json.totalPrice, 10);
		      	 self.shipping=json.shipping;
		      	 self.updateCartPopup();
		        });
	},

	sendCart: function() {
		//send the current cart object to the server to be stored in session.
		var payload = { "productids" : this.items, "totalPrice": this.itemsTotal};
		$.ajax({
			async: false,
			cache: false,
			type: "POST",
			dataType: 'json',
			data: payload,
			url: "php/ajax.php?cartUpdate"
			});
	},
	emptyCart : function(){
		//add a cart item to the cart object and update the cart total items
		this.items=[];
		this.itemsTotal=0;
		this.updateCartPopup();
    	$("div#cart-items-all").find(".cart-header").remove();
    	$("div#cart-cost").empty();
    	$("div#cart-items-all").append("<p class='text-uppercase'>your shopping bag is empty</p>");

	},
	openCloseCart: function() {
          window.setTimeout(function() {
                      $("a.dropdown-toggle.cart").dropdown("toggle");
                  }, 500);
         window.setTimeout(function() {
                      $("a.dropdown-toggle.cart").dropdown("toggle");
                      $("a.dropdown-toggle.cart").blur();
                  },2000);
	},
	updateCartPopup: function(){
		$("div.cart-box span.simpleCart_total"). html("&#8377;"+this.formatCurrency(this.getCartTotalPrice()));
    	$("div.cart-box span.simpleCart_quantity"). html(this.getCartTotalItems());
    	$("#badge"). html(this.getCartTotalItems());
	},
	updateCart : function(pid, pprice){
		//add a cart item to the cart object and update the cart total items
		pprice = parseFloat(pprice, 10);
		pid = pid+"";
		this.items.push(pid);
		this.itemsTotal = this.itemsTotal + pprice;
		this.updateCartPopup();
	},
	removeItem: function(pid, pprice){
		pprice = parseFloat(pprice, 10);
		var ix = this.items.indexOf(pid);
		this.items.splice(ix, 1);
		this.itemsTotal = this.itemsTotal - pprice;
		this.updateCartPopup();
	}
};

$(window).on('beforeunload', function(){
	window.cart.sendCart();
	return void(0);
});

$(document).ready(function(){
	window.cart.getCart();
});

