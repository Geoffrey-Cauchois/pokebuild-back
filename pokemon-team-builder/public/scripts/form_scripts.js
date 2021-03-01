var app = {

  init: function() {
    let searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.classList.add('form__search')
    searchInput.addEventListener('input', app.handleFilterPokemonCheckboxes)
    document.querySelector('.form__header').appendChild(searchInput);
    
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
  }
}

document.addEventListener('DOMContentLoaded', app.init);