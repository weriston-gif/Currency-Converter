<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,700&display=swap">

    <!-- JS do Bootstrap (opcional) -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    .container-base {
        left: 320px;
        top: 166px;
        border-radius: 16px;
        padding: 64px 80px 64px 80px;
        background: #FFFF !important;
    }

    body {
        background: #00BFFF !important;
        font-family: 'Poppins', sans-serif;

    }
</style>

<body>
    <div class="container">
        <div class="text-center my-4">
            <h3><strong> Currency Converter: </strong></h3>
            <h4>Obtenha as últimas taxas de câmbio entre duas moedas</h4>
        </div>
        <div class="container-base">

            <div>
                <div class="d-flex align-items-baseline">
                    <p>Saiba o valor atualizado da conversão</p>
                    <iframe src="https://giphy.com/embed/Pxj2KTQfem75GWBxjo" width="36" height="36" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                </div>

                <div class="d-flex justify-content-between align-items-baseline">
                    <select id="firstSelect" class="form-control w-25">
                        @foreach ($moedas ?? '' as $moeda)

                        <option class="option-value" value="{{ $moeda }}">{{$moeda}}</option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                    <select id="secondSelect" class="form-control w-25">
                        @foreach ($moedas ?? '' as $moeda)

                        <option class="option-value" value="{{ $moeda }}">{{$moeda}}</option>
                        @endforeach
                    </select>
                </div>
                <div id="loading-gif" style="display:none;">
                    <iframe src="https://giphy.com/embed/IwSG1QKOwDjQk" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                </div>
                <div id="currency-results"></div>
                <div class="container">
                    <canvas id="currency-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function exibirElementos(response) {
        var elementos = $(this);
        elementos.forEach(function(elemento) {
            var div = $('<div>').text(elemento.nome);
            $('#container').append(div);
        });
    }
    $(document).ready(function() {
        $('#firstSelect, #secondSelect').change(function() {
            var firstSelectValue = $('#firstSelect').val();
            var secondSelectValue = $('#secondSelect').val();
            if (firstSelectValue && secondSelectValue != '') {
                $('#loading-gif').show(); // Mostra o GIF de loading
                $.ajax({
                    url: '/currencies/update',
                    type: 'POST',
                    data: {
                        firstSelectValue: firstSelectValue,
                        secondSelectValue: secondSelectValue,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $('#loading-gif').hide(); // Esconde o GIF de loading após o carregamento

                        if (response.error) {
                            // Display error message
                            $('#currency-results').html(response.error);
                        } else {

                            // Display currency data
                            var table = $('<table class="table table-hover">');
                            var thead = $('<thead>').appendTo(table);
                            var tr = $('<tr>').appendTo(thead);
                            $('<th>').text('Moeda').appendTo(tr);
                            $('<th>').text('Moeda variante da taxação').appendTo(tr);
                            $('<th>').text('Nome das moedas').appendTo(tr);
                            $('<th>').text('Alta').appendTo(tr);
                            $('<th>').text('Queda').appendTo(tr);
                            $('<th>').text('Compra').appendTo(tr);
                            $('<th>').text('Venda').appendTo(tr);
                            $('<th>').text('Variação').appendTo(tr);
                            $('<th>').text('Data da atualizaçã').appendTo(tr);
                            var tbody = $('<tbody>').appendTo(table);
                            $.each(response, function(index, element) {
                                tr = $('<tr>').appendTo(tbody);
                                $('<td>').text(element.code).appendTo(tr);
                                $('<td>').text(element.codein).appendTo(tr);
                                $('<td>').text(element.name).appendTo(tr);
                                $('<td>').text(element.high).appendTo(tr);
                                $('<td>').text(element.low).appendTo(tr);
                                $('<td>').text(element.bid).appendTo(tr);
                                $('<td>').text(element.ask).appendTo(tr);
                                $('<td>').text(element.varBid).appendTo(tr);
                                $('<td>').text(new Date(element.timestamp * 1000)).appendTo(tr);
                            });
                            $('#currency-results').html(table);

                            $.get('https://economia.awesomeapi.com.br/json/daily/' + firstSelectValue + '-' + secondSelectValue + '/30').done(function(response) {
                                // Extract the high and low values from the response
                                var highs = response.map(function(element) {
                                    return element.high;
                                });
                                var lows = response.map(function(element) {
                                    return element.low;
                                });

                                // Create a new chart using Chart.js
                                var ctx = document.getElementById('currency-chart').getContext('2d');
                                var chart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: response.map(function(element) {
                                            return new Date(element.timestamp * 1000).toLocaleDateString();
                                        }),
                                        datasets: [{
                                                label: 'Alta',
                                                data: highs,
                                                borderColor: 'red',
                                                fill: false
                                            },
                                            {
                                                label: 'Queda',
                                                data: lows,
                                                borderColor: 'blue',
                                                fill: false
                                            }
                                        ]
                                    },
                                    options: {
                                        scales: {
                                            xAxes: [{
                                                type: 'time',
                                                time: {
                                                    unit: 'day',
                                                    displayFormats: {
                                                        day: 'MMM D'
                                                    }
                                                }
                                            }],
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: true
                                                }
                                            }]
                                        }
                                    }
                                });
                            });

                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.log(error);
                    }
                });


            }
        });
    });
    // Retrieve the historical data for the currency pair
</script>