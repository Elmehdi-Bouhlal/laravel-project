<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMuHq2b4LxvILK4pEY2hpY+U7y8Ne3j6c5XW9E" crossorigin="anonymous">
    <style>
       h4 {
            font-weight: 600;
            color: #555;
            margin-top: 12px;
            margin-bottom: 18px;
            font-family: inherit;
            line-height: 1;
        }         
        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent; 
            transition: border 0.3s;
            width: calc(33.33% - 20px); 
        }
        .image-container.selected {
            border-color: #007bff; /* Couleur de la bordure lorsqu'une image est sélectionnée */
        }
        .image-container img {
            width: 100%; 
            height: 90px; 
            object-fit: cover; 
        }
        .selection-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 123, 255, 0.5); /* Couleur de la superposition */
            display: none; /* Masquer par défaut */
        }
        .image-container.selected .selection-overlay {
            display: block; /* Afficher la superposition lorsqu'elle est sélectionnée */
        }
        .check-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px;
            color: white;
            display: none; /* Masquer par défaut */
            transition: opacity 0.3s; /* Animation de l'icône */
        }
        .image-container.selected .check-icon {
            display: block; /* Afficher l'icône lorsqu'elle est sélectionnée */
            opacity: 1; /* Afficher l'icône */
        }
        .image-name {
            text-align: center;
            margin-top: 5px; /* Espace entre l'image et le nom */
            font-size: 14px; /* Taille de la police pour le nom */
            overflow: hidden; /* Masquer le texte qui dépasse */
            white-space: nowrap; /* Ne pas couper les lignes */
            text-overflow: ellipsis; /* Ajouter '...' à la fin du texte qui dépasse */
            width: 100%; /* Largeur du conteneur pour le texte */
        }
        .modal-body {
            max-height: 60vh; /* Hauteur maximale du conteneur d'images */
            overflow-y: auto; /* Ajoute la barre de défilement verticale si nécessaire */
        }
        .custom-button{
            width: 47%;
            border-radius: 0px ;
        }    
        .input-search{
            padding: 10px 15px; /* Espacement interne */
            border: 1px solid #ced4da; /* Bordure grise */
            border-radius: 0px; /* Coins arrondis */
            font-size: 16px; /* Taille de la police */
            transition: box-shadow 0.3s, border-color 0.3s;         
        }    
        .input-search:focus {
            outline: none; /* Pas d'outline par défaut */
            border-color: silver; /* Bordure bleue au focus */
            box-shadow: 0 4px 8px rgba(58, 58, 58, 0.3); /* Ombre au focus */
        }
        .input-search::placeholder {
            color: #6c757d; /* Couleur des placeholders */
        }
        .search-elements{
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }     
        .error-message{
            width: 100%;
            height: 100%;
            display: none;    
            text-align: center;        
            justify-content: center;
            align-items: center;
        }   
    </style>
</head>
<body>
<!-- Button trigger modal -->
<button type="button" onclick="getUrlImages(1)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
    Select a new image
</button>
  
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" >
      <div class="modal-content">
        <div class="modal-header">
            <div class="text-center w-100">
                <h5 class="modal-title" style="word-spacing: -2px;" id="exampleModalLongTitle">Choose an image</h5>
            </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="search-elements gap-1">
            <input type="text" class="input-search" placeholder="Recherchez...">  
            <button class="btn btn-primary" style="border-radius: 0px;" onclick="searchEvent()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </button>                                                 
        </div>        
        <div class="error-message">

        </div>
        <div class="modal-body" id="imageContainer" class="d-flex flex-wrap justify-content-center">
          <!-- Images will be displayed here -->
        </div>
        <nav id="pagination" class="mt-3">
            <ul class="pagination justify-content-center">
              <li class="page-item">
                <button class="page-link" onclick="changePage('prev')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                </button>
              </li>
              <li class="page-item">
                <button class="page-link" onclick="changePage('next')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                </button>
              </li>
            </ul>
        </nav>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary custom-button" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary custom-button">Save changes</button>
        </div>        
      </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var keyPexels = "p88QiXY1U8yqC4UGBhVf1pZxuPvxD2p46hZpqxMzT7QWgjTQww5U01Ob";
    var keyword = "test";
    var numberOfImage = 12;
    var currentPage = 1;

    $.ajaxSetup({
        headers: {
            "Authorization": keyPexels
        }
    });

    async function getUrlImages(page) {
        currentPage = page; // Mettre à jour la page actuelle
        try {
            const response = await $.ajax({
                type: "get",
                url: `https://api.pexels.com/v1/search?query=${keyword}&per_page=${numberOfImage}&page=${currentPage}&auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280`,
                headers: {
                    "Authorization": keyPexels // Ajoutez l'en-tête d'autorisation
                }
            });

            console.log(response);

            displayImages(response.photos); 
            
            // Vérifiez si les données existent
            if (!response || !response.photos || response.photos.length === 0) {
                throw new Error("Empty data");
            }        

            // Renvoie un objet indiquant le succès
            return { status: true };    
        } catch (error) {
            console.log("Error occurred:", error.message);        
            return { status: false }; 
        }    
    }



    function displayImages(photos) {
        const imageContainer = $('#imageContainer');
        imageContainer.empty(); // Vide le conteneur avant d'ajouter de nouvelles images
        photos.forEach(photo => {
            const imgContainer = $('<div class="image-container"></div>');
            const img = $('<img>').attr('src', photo.src.small);
            const overlay = $('<div class="selection-overlay"></div>');
            const checkIcon = $('<i class="fas fa-check check-icon"></i>'); // Icône de sélection
            
            // Troncature du nom de l'image si nécessaire
            const imageName = photo.alt ? 
                $('<div class="image-name"></div>').text(photo.alt.length > 10 ? photo.alt.slice(0, 10) + "..." : photo.alt) : 
                $('<div class="image-name"></div>').text("Image");

            imgContainer.append(img).append(overlay).append(checkIcon).append(imageName);

            imgContainer.click(() => {
                // Gérer la sélection de l'image
                $('.image-container').removeClass('selected'); // Retirer la sélection de toutes les images
                imgContainer.addClass('selected'); // Ajouter la sélection à l'image actuelle
                console.log('Selected image URL:', photo.src.original);
            });

            imageContainer.append(imgContainer); // Ajoute chaque image au conteneur
        });
    }

    function changePage(direction) {
        if (direction === 'prev' && currentPage > 1) {
            getUrlImages(currentPage - 1);
        } else if (direction === 'next') {
            getUrlImages(currentPage + 1);
        }
    }
    async function searchEvent(){                
        keyword = $(".input-search").val() ;        
        let response = await getUrlImages(currentPage) ;
        if(response.status === false){
            $(".error-message").html("<p class='my-5'>sorry user , </br>there are no result withe this keyword</p>")
            $('.error-message').css('display', 'flex');
        }else{
            $('.error-message').css('display', 'none');
        }
    }
</script>
</body>
</html>
