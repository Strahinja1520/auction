const inputs = document.querySelectorAll("input");
// console.log(inputs);
const errors = {
  reg_forename: [],
  reg_lastname: [],
  reg_username: [],
  reg_email: [],
  reg_phone: [],
  reg_password_1: [],
  reg_password_2: [],
};
// console.log(errors);

inputs.forEach((input) => {
  input.addEventListener("change", (e) => {
    let currentInput = e.target;
    let inputValue = currentInput.value;
    let inputName = currentInput.name;
    let inputOK = 1;

    errors[inputName] = [];

    switch (inputName) {
      case "reg_forename":
        const patternForename = /^[A-Z][a-z]{2,32}$/;
        if (!patternForename.test(inputValue)) {
          errors[inputName].push(
            "Ime mora poceti velikim slovom. Mora imati barem 3 karaktera."
          );
        } else {
          inputOK = 0;
        }
        break;
      case "reg_lastname":
        const patternLastname = /^[A-Z][a-z]{2,32}$/;
        if (!patternLastname.test(inputValue)) {
          errors[inputName].push(
            "Ime mora poceti velikim slovom. Mora imati barem 3 karaktera."
          );
        } else {
          inputOK = 0;
        }
        break;
      case "reg_username":
        const patternKorisnicko = /^[A-Za-z0-9]{6,32}$/;
        if (!patternKorisnicko.test(inputValue)) {
          errors[inputName].push(
            "Korisnicko ime ne sme imati specijalne znake. Mora imati barem 6 karaktera."
          );
        } else {
          inputOK = 0;
        }
        break;
      case "reg_email":
        const patternEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if (!patternEmail.test(inputValue)) {
          errors[inputName].push("Niste uneli ispravan format e-mail adrese.");
        } else {
          inputOK = 0;
        }
        break;
      case "reg_phone":
        const patternPhone = /^06\d{7,8}$/;
        if (!patternPhone.test(inputValue)) {
          errors[inputName].push("Niste uneli ispravan format broja telefona.");
        } else {
          inputOK = 0;
        }
        break;
      case "reg_password_1":
        const patternLozinka =
          /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        if (!patternLozinka.test(inputValue)) {
          errors[inputName].push(
            "Password nije dobar! Morate imati mala slova, velika slova, brojke i specijalne karaktere!Min 8 karaktera."
          );
        } else {
          inputOK = 0;
        }
        break;
      case "reg_password_2":
        let lozinka = document.getElementById("input_password_1");
        if (inputValue !== lozinka.value) {
          errors[inputName].push("Unete lozinke se ne poklapaju.");
        } else {
          inputOK = 0;
        }
        break;
      case "reg_address":
        const patternAdress = /^.*$/;
        if (!patternAdress.test(inputValue)) {
          errors[inputName].push(
            "Password nije dobar! Morate imati mala slova, velika slova, brojke i specijalne karaktere!Min 8 karaktera."
          );
        } else {
          inputOK = 0;
        }
        break;
    }
    prikaziGresku(currentInput);
    // console.log(errors);

    const submitBtn = document.querySelector(".button-form");
    if (inputOK) {
      submitBtn.setAttribute("disabled", "true");
      currentInput.style.borderColor = "red";
    } else {
      submitBtn.removeAttribute("disabled");
      currentInput.style.borderColor = "#75dec7";
    }
    // console.log(inputOK);
    // console.log(submitBtn);
  });
});

const prikaziGresku = (input) => {
  let parentDiv = input.parentElement;
  if (parentDiv.querySelector("ul")) parentDiv.querySelector("ul").remove();
  let errorUl = document.createElement("ul");
  parentDiv.appendChild(errorUl);
  let errorLi = document.createElement("li");
  errorLi.innerText = errors[input.name];
  errorUl.appendChild(errorLi);
};
