var app = {

  apiBaseUrl: 'http://ec2-54-209-63-59.compute-1.amazonaws.com/api/v1/',

  init: function() {
    let searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.classList.add('form__search')
    searchInput.addEventListener('input', app.handleFilterPokemonCheckboxes)
    document.querySelector('.form__header').appendChild(searchInput);
    
    let formTopContainer = document.querySelector('div.form__pokemon-label');
    let skillsDiv = document.createElement('div');
    skillsDiv.classList.add('form__skills');
    formTopContainer.appendChild(skillsDiv);
    let checkboxes = document.querySelectorAll('#team_pokemon > .form-check > input');
    checkboxes.forEach(function(currentValue){
      currentValue.addEventListener('click', app.handleAbilitySelectorForCheckBox);
    })
  },

  handleFilterPokemonCheckboxes: function(evt) {
    let inputValue = evt.target.value.toLowerCase();
    
    let pokemonLabels = document.querySelectorAll('#team_pokemon > .form-check');
    
    pokemonLabels.forEach(function(currentValue){
      
      let pokemonValue = currentValue.childNodes[3].textContent.toLowerCase();

      if(pokemonValue.includes(inputValue) == false){

        currentValue.classList.add('hidden');

        currentValue.childNodes[1].classList.add('hidden');

        currentValue.childNodes[3].classList.add('hidden');
      }
      else if(pokemonValue.includes(inputValue)){

        currentValue.classList.remove('hidden');

        currentValue.childNodes[1].classList.remove('hidden');

        currentValue.childNodes[3].classList.remove('hidden');
      }
    })
  },

  handleAbilitySelectorForCheckBox: function(evt){
    let selectedPokemon = evt.target.nextElementSibling.textContent.toLowerCase();
    console.log(selectedPokemon);

    let fetchOptions = {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache'
    };
    request = fetch(app.apiBaseUrl + 'pokemon/' + selectedPokemon);
    request.then(
      function(response) {
        return response.json();
      })
    .then(
      function(jsonResponse){
        abilities = jsonResponse.resistanceModifyingAbilitiesForApi;
        console.log(abilities);

        if(abilities.length != 0){

          if(evt.target.checked){          
            let formDiv = document.createElement('div')
            formDiv.classList.add('form__skill-' + selectedPokemon, 'form__skill');
            let select = document.createElement('select');
            select.name =  selectedPokemon +'Ability';
            select.id = selectedPokemon +'Ability';
            let label = document.createElement('label');
            label.for = 'ability';
            label.textContent = 'Choisissez une capacité pour ' + selectedPokemon
            let defaultOption = document.createElement('option');
            for(let ability in abilities){
              let abilityOption = defaultOption.cloneNode();
              abilityOption.value = abilities[ability];
              abilityOption.textContent = abilities[ability];
              select.appendChild(abilityOption);
            }
            defaultOption.value = null;
            defaultOption.textContent = 'Aucune capacité';
            select.prepend(defaultOption);
            select.value = null;
            formDiv.appendChild(select);
            formDiv.appendChild(label);
            let formContainer = document.querySelector('.form__skills');
            formContainer.appendChild(formDiv);
          }
          else{
            document.querySelector('.form__skill-' + selectedPokemon).remove();
          }
        }
      }
    )
    

    
  }
}

document.addEventListener('DOMContentLoaded', app.init);