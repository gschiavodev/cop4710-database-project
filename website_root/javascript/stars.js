window.addEventListener('load', function()
{

    let stars = Array.from(document.querySelectorAll('.star'));
    let ratingInput = document.getElementById('rating');
    let rating = 4;

    // Highlight stars according to default rating
    for(let i = 0; i < rating; i++)
    {
        stars[i].classList.add('highlight');
    }

    // Update the rating input value to the default rating
    ratingInput.value = rating;

    stars.forEach((star, index) =>
    {
        star.addEventListener('mouseover', function()
        {
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
            let value = parseInt(this.getAttribute('data-value'));
            ratingInput.value = value;
            rating = value;

            // Remove highlight from all stars
            stars.forEach(star => star.classList.remove('highlight'));

            // Highlight this star and all stars before it
            for(let i = 0; i < value; i++)
            {
                stars[i].classList.add('highlight');
            }
        });
        
    });

});
