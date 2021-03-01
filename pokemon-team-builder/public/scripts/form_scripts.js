var app = {

  init: function() {
    let searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.classList.add('form__search')
    searchInput.addEventListener('input', app.handleFilterPokemonCheckboxes)
    document.querySelector('#team_pokemon').prepend(searchInput);
    
  },

  handleFilterPokemonCheckboxes: function(evt) {
    let inputValue = evt.target.value.toLowerCase();
    
    let pokemonLabels = document.querySelectorAll('#team_pokemon > label');
    
    pokemonLabels.forEach(function(currentValue){
      
      let pokemonValue = currentValue.textContent.toLowerCase();

      if(pokemonValue.includes(inputValue) == false){

        currentValue.classList.add('hidden');

        currentValue.previousElementSibling.classList.add('hidden');
      }
      else if(pokemonValue.includes(inputValue)){

        currentValue.classList.remove('hidden');

        currentValue.previousElementSibling.classList.remove('hidden');
      }
    })
  }
}

document.addEventListener('DOMContentLoaded', app.init);