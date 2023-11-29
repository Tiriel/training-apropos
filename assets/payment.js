import JSConfetti from 'js-confetti';

const jsConfetti = new JSConfetti();
document.querySelector('h1').addEventListener('click', function (e) {
    console.log('Clicked!');
    jsConfetti.addConfetti();
})
