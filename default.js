document.addEventListener('DOMContentLoaded', () =>
{
    document.querySelectorAll('[type="tel"]').forEach(value =>
    {
        value.addEventListener('keyup', e =>
        {
            tel = e.target.value;
            tel = tel.replace(/\D/g, '');
            tel = tel.replace(/^(\d{2})(\d)/g, '($1) $2');
            tel = tel.replace(/(\d)(\d{4})$/, '$1-$2');

            e.target.value = tel;
        });
    });
});

function notification(status, message)
{
    const card = document.createElement('div');

    card.innerHTML = message[status]; 
    card.classList.add('notification', status);   
    document.body.insertBefore(card, document.body.firstChild);

    setTimeout(() =>
    {
        card.remove();

    }, 1000 * 3);
}

function logradouros()
{
    fetch('logradouros.json')
    .then(response => 
    {
        if (response.ok)
        {
            return response.json();
        }

        console.log(response.status);
    })
    .then(json =>
    {
        const container = document.createElement('datalist');
        container.setAttribute('id', 'logradouros');

        json.logradouros.forEach(item =>
        {
            option = document.createElement('option');
            option.textContent = item;

            container.appendChild(option);
        });

        document.body.appendChild(container);

    })
    .catch(error =>
    {
        console.error('Erro ao carregar:', error);
    });
}