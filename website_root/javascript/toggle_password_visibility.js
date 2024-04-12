window.addEventListener('load', function () 
{

    document.getElementById('toggle_password').addEventListener('click', function () 
    {
        
        // Get the password input field
        var password_input = document.getElementById('password');

        // Get the toggle password button
        var toggle_password = document.getElementById('toggle_password');

        // Toggle the type attribute and text content of the button
        password_input.type = password_input.type === 'password' ? 'text' : 'password';
        toggle_password.textContent = password_input.type === 'password' ? 'Show' : 'Hide';

    });

});