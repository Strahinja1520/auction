const title = document.querySelector(".auction-show-title h1");

const titleTag = document.querySelector("#auction-title");
const titleText = titleTag.getAttribute("data-title");

if (titleText.length > 40) {
  title.style.fontSize = "20px";
}

const modal_offer = document.querySelector(".popup-modal-offer");
const overlay_offer = document.querySelector(".overlay-offer");

const lastOffersBtn = document.querySelector(".show-offers");
const closeLastOffers = document.querySelector(".close-offers");

lastOffersBtn.addEventListener("click", (e) => {
  modal_offer.style.display = "flex";
  overlay_offer.style.display = "block";
});
closeLastOffers.addEventListener("click", (e) => {
  modal_offer.style.display = "none";
  overlay_offer.style.display = "none";
});

const modal = document.querySelector(".popup-modal");
const overlay = document.querySelector(".overlay");

const offerBtn = document.querySelector(".btn-offer");
const closeModal = document.querySelector(".close-modal");

offerBtn.addEventListener("click", (e) => {
  modal.style.display = "flex";
  overlay.style.display = "block";
});
closeModal.addEventListener("click", (e) => {
  modal.style.display = "none";
  overlay.style.display = "none";
});

const startPriceTag = document.querySelector(".start-price");
const currentPriceTag = document.querySelector(".current-price");

let startPrice = startPriceTag.getAttribute("data-price");
let currentPrice = currentPriceTag.getAttribute("data-price");
startPrice = Number(startPrice);
currentPrice = Number(currentPrice);
if (isNaN(currentPrice)) {
  currentPrice = startPrice;
  console.log(currentPrice);
}
startPrice = startPrice + startPrice * 0.07;
currentPrice = currentPrice + currentPrice * 0.07;

console.log(startPrice);
console.log(currentPrice);

const btnSub = document.querySelector("#submit");
btnSub.addEventListener("click", (e) => {
  let error = document.querySelector(".error");
  let offerPriceTag = document.querySelector(".offerPrice");
  let offerPrice = offerPriceTag.value;

  console.log(offerPrice);
  if (offerPrice < startPrice) {
    error.innerText = "Cena mora biti veca za 7% od prethodne";
    e.preventDefault();
  }
  if (offerPrice < currentPrice) {
    error.innerText = "Cena mora biti veca za 7% od prethodne";
    e.preventDefault();
  }
});

const dateExpireTag = document.querySelector(".expire-date");
const dateExpire = dateExpireTag.getAttribute("data-datetime");
const datumIsteka = new Date(dateExpire);

function azurirajTimer() {
  const trenutniDatum = new Date();
  const razlika = datumIsteka - trenutniDatum;

  if (isNaN(datumIsteka)) {
    document.getElementById("timer").style.display = "none";
    return;
  }

  if (razlika > 94282434) {
    document.getElementById("timer").style.display = "none";
    return;
  }
  console.log("RADI");
  if (razlika < 0) {
    document.getElementById("timer").innerHTML = "AUKCIJA JE ISTEKLA";
  } else {
    const sati = Math.floor(razlika / (1000 * 60 * 60));
    const minuti = Math.floor((razlika % (1000 * 60 * 60)) / (1000 * 60));
    const sekunde = Math.floor((razlika % (1000 * 60)) / 1000);

    document.getElementById("timer").innerHTML = `Preostalo vreme: ${
      sati < 10 ? "0" : ""
    }${sati}h ${minuti < 10 ? "0" : ""}${minuti}m ${
      sekunde < 10 ? "0" : ""
    }${sekunde}s`;
  }
}
setInterval(azurirajTimer, 1000);

azurirajTimer();
