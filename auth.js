const apikey = localStorage.getItem('apikey');
const regex = /^[a-fA-F0-9]{32}$/;

if (apikey === null || regex.test(apikey) == false)
{
    window.location.href = 'index.html';
}