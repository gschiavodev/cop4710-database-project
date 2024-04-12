window.addEventListener('load', function()
{

    let stars = Array.from(document.querySelectorAll('form .star-rating .star'));
    let rating_input = document.getElementById('rating');

    // Get the default rating from the input value
    let rating = document.querySelector('#rating').value;

    // Highlight stars according to default rating
    for(let i = 0; i < rating; i++)
    {
        stars[i].classList.add('highlight');
    }

    // Update the rating input value to the default rating
    rating_input.value = rating;

    stars.forEach((star, index) => 
    {

        star.addEventListener('mouseover', function() 
        {

            // Remove highlight from all stars
            stars.forEach(star => star.classList.remove('highlight'));

            // Highlight this star and all stars before it
            for(let i = 0; i <= index; i++) 
            {
                stars[i].classList.add('highlight');
            }

        });

        star.addEventListener('mouseout', function() 
        {

            // Remove highlight from all stars
            stars.forEach(star => star.classList.remove('highlight'));

            // If a star has been clicked, keep it and the preceding stars highlighted
            for(let i = 0; i < rating; i++) 
            {
                stars[i].classList.add('highlight');
            }

        });

        star.addEventListener('click', function() 
        {

            // Update the rating input value
            let value = parseInt(this.getAttribute('data-value'));
            rating_input.value = value;

            // Update the rating variable
            rating = value;

        });
        
    });

});
