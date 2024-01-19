const selectElementMarka = document.getElementById('markaAuc');
const selectElementModel = document.getElementById('modelAuc');
const responseElement = document.getElementById('responseP');

selectElementMarka.addEventListener('change', async function (event) {
    const selectedValue = selectElementMarka.value;
    const url = '/model.php';
    const data = { marka: selectedValue };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            createModelSelectOptions(data);
            // responseElement.innerText = ;
            // console.log(data); // Содержимое ответа от сервера
        })
        .catch(function (error) {
            // Обработка ошибок, если необходимо
        });

});

const createModelSelectOptions = (data) => {
    console.log(data);

    let dataFotmat = Array(data.data);
    console.log('dataFotmat[0]', dataFotmat[0]);
    selectElementModel.innerHTML = '';

    // Проверяем, что статус ответа 'success' и что в 'data' есть массив
    if (data) {
        dataFotmat[0].forEach(model => {
            let option = document.createElement('option');
            option.value = model.value; // Используем 'value' из объекта модели
            option.text = model.text; // Используем 'text' из объекта модели
            console.log('OPTIONS', option);

            selectElementModel.appendChild(option);
        });
    } else {
        let option = document.createElement('option');
        option.value = '';
        option.text = 'No models available';
        selectElementModel.appendChild(option);
    }
}

