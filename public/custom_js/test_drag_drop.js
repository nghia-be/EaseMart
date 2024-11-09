/* Dragula JS */
dragula([document.getElementById('cards'), document.getElementById('order')])
	.on('drag', function(el) {
		console.log('test');
		el.className.replace('ex-moved', '');
	}).on('drop', function(el) {
		el.className += 'ex-moved';
	}).on('over', function(el, container) {
		container.className += 'ex-over';
	}).on('out', function(el, container) {
		container.className.replace('ex-over', '');
	});

/* Vanilla JS to add a new card */
function addCard() {
	/* Get card text from input */
	var inputCard = document.getElementById("cardText").value;
	/* Add card to the 'To Do' column */
	document.getElementById("cards").innerHTML += "<li class='card'><p>" + inputCard + "</p></li>";
	/* Clear card text from input after adding card */
	document.getElementById("cardText").value = "";
}

/* Vanilla JS to delete all cards */
function deleteAllCards() {
	/* Clear cards from 'cards' and 'order' column */
	document.getElementById("cards").innerHTML = "";
	document.getElementById("order").innerHTML = "";
}

/* Vanilla JS to delete all cards in cards column */
function deleteCardsCards() {
	/* Clear cards from 'cards' column */
	document.getElementById("cards").innerHTML = "";
}

/* Vanilla JS to delete all cards in order column*/
function deleteOrderCards() {
	/* Clear cards from 'order' column */
	document.getElementById("order").innerHTML = "";
}