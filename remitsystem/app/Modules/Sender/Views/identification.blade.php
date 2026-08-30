<!DOCTYPE html>
<html>
<title>Image</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
    .mySlides {display:none;}
</style>
<body>

<h2 class="w3-center">Identification Image</h2>

<div class="w3-content w3-display-container">

        <img src="{{ url( '/identification' , $identification->name) }}" class="mySlides" style="width:100%"/>

</div>

<script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length}
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[slideIndex-1].style.display = "block";
    }
</script>

</body>
</html>
