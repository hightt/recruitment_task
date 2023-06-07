<?php require_once('partials/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mt-3">
                <div class="mb-3">
                    <label for="amount" class="form-label">Kwota</label>
                    <input type="number" class="form-control" name="amount" id="amount" placeholder="Przykład: 750" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Waluta źródłowa</label>
                    <select id="source-currency" class="form-select">
                        <option selected disabled>Wybierz</option>
                        <?php foreach($currencies as $currency): ?>
                            <option value="<?= $currency['id']; ?>" data-created-at="<?= $currency['created_at'];?>" data-rate="<?= $currency['value']; ?>"><?= sprintf("%s - %s", $currency['code'], $currency['name']); ?></option>
                        <?php endforeach ?>
                    </select>
                    <span id="source-currency-info" style="font-size: 12px; font-style: italic; color: #757575;"></span>
                </div>
                <div class="mb-3">
                <label for="amount" class="form-label">Waluta docelowa</label>
                    <select id="target-currency" class="form-select">
                        <option selected disabled>Wybierz</option>
                        <?php foreach($currencies as $currency): ?>
                            <option value="<?= $currency['id']; ?>" data-created-at="<?= $currency['created_at'];?>" data-rate="<?= $currency['value']; ?>"><?= sprintf("%s - %s", $currency['code'], $currency['name']); ?></option>
                        <?php endforeach ?>
                    </select>
                    <span id="target-currency-info" style="font-size: 12px; font-style: italic; color: #757575;"></span>
                </div>
                <button type="submit" id="btn-calculate" class="btn btn-primary">Oblicz wynik</button>
        </div>
        <div class="col-lg-6">
            <p>
            <h4>Aby skorzystać z kalkulatora, wprowadź trzy kluczowe informacje:</h4>
            <ol>
                <li>
                    Kwotę do przeliczenia: W tym polu użytkownik wpisuje wartość, którą chce przeliczyć na inną walutę. 
                </li><br>
                <li>
                    Walutę źródłową: W tym polu użytkownik wybiera walutę, z której chce przeliczyć kwotę. 
                </li><br>
                <li>
                    Walutę docelową: W tym polu użytkownik wybiera walutę, na którą chce przeliczyć kwotę. 
                </li><br>
            </ol>
        </div>
    </div>
    <div id="errors-div" class="col-12 mt-3" style="display: none;">
        <div id="errors-msg" class="alert alert-danger" role="alert"></div>
    </div>
    <div class="row">
        <div class="col-lg-6" id="result-box" style="display: none;">
            <label for="amount" class="form-label m-0">Kurs waluty źródłowej</label>
            <input type="text" class="form-control" id="source-currency-rate" disabled>
            <label for="amount" class="form-label m-0">Kurs waluty docelowej</label>
            <input type="text" class="form-control" id="target-currency-rate" disabled>

            <h3 class="text-success mt-3" id='final-result'></h3>
        </div>
    </div>
</div>

<script>

$('#btn-calculate').click(function(){
    var formData = {
        amount: $('#amount').val(),
        sourceCurrencyId: $('#source-currency').val(),
        targetCurrencyId: $('#target-currency').val()
    }

    $.ajax({
        type: "POST",
        url: "/calculate",
        data: {formData: formData},
        success: function(data) {
            $("#errors-div").hide();
            $("#result-box").show();
            $("#source-currency-rate").val(data.sourceCurrencyRate.value + " PLN");
            $("#target-currency-rate").val(data.targetCurrencyRate.value + " PLN");

            var currencyType = $('#target-currency option:selected').text().substring(0, 3);
            console.log(currencyType);
            $("#final-result").text("Wynik: " + data.result + " " + currencyType);
        },
        error: function(data) {
            $("#errors-div").show();
            $("#result-box").hide();
            $("#errors-msg").text(data.responseJSON[0]);

        }
    });
 });

 $('#source-currency, #target-currency').change(function(){
    var selectId = $(this).attr('id');
    var spanId = selectId + "-info";
    var selectedOption = $(this).find('option:selected');
    $("#" + spanId).text("Aktualny kurs: " + selectedOption.data('rate').toFixed(3) + " PLN. Ostatnia aktualizacja: " + selectedOption.data('created-at'));

});

</script>
<?php require_once('partials/footer.php'); ?>
