<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher/Cacher Élément</title>
    <style>
        /* Cache les éléments par défaut */
        .element1, .element2 {
            display: none;
        }

        /* Affiche l'élément 1 si le premier radio est coché */
        #showElement1:checked ~ .element1 {
            display: block;
        }

        /* Affiche l'élément 2 si le second radio est coché */
        #showElement2:checked ~ .element2 {
            display: block;
        }
    </style>
</head>
<body>

    <label>
        <input type="radio" name="toggle" id="showElement1" checked>
        Afficher Élément 1
    </label>

    <label>
        <input type="radio" name="toggle" id="showElement2">
        Afficher Élément 2
    </label>

    <!-- Les éléments doivent être au même niveau que les inputs pour que ~ fonctionne -->
    <div class="element1">Élément 1</div>
    <div class="element2">Élément 2</div>

    <div class="toggle-container">
      <input type="radio" name="toggle" id="showElement1" checked>
      <label for="showElement1">Afficher Élément 1</label>
  
      <input type="radio" name="toggle" id="showElement2">
      <label for="showElement2">Afficher Élément 2</label>
  
      <div class="element1">Élément 1</div>
      <div class="element2">Élément 2</div>
  </div>
  
  <style>
      .element1, .element2 {
          display: none;
      }
  
      #showElement1:checked ~ .element1 {
          display: block;
      }
  
      #showElement2:checked ~ .element2 {
          display: block;
      }
  </style>

</body>
</html>

