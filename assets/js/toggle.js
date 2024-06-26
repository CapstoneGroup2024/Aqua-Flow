function toggleRadio(label) {
    var radios = document.querySelectorAll('input[type="radio"]');
    var cards = document.querySelectorAll('.changeBorder');

    // Reset border color for all cards
    cards.forEach(function(card) {
        card.style.borderColor = 'transparent';
    });

    // Set border color for the clicked label's card
    radios.forEach(function(radio) {
        if (radio.checked) {
            var card = label.querySelector('.changeBorder');
            if (card) {
                card.style.borderColor = '#6FC7EA';
            }
        }
    });
}
