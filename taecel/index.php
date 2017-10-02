

<script>
var data = null;

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function () {
  if (this.readyState === 4) {
    console.log(this.responseText);
  }
});

xhr.open("POST", "https://taecel.com/app/api/getProducts?key=25d55ad283aa400af464c76d713c07ad&nip=25d55ad283aa400af464");
xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
xhr.setRequestHeader("content-length", "64");
xhr.setRequestHeader("cache-control", "no-cache");
xhr.setRequestHeader("postman-token", "e842798c-31c3-d47a-adee-e1ff6a4d12b6");

xhr.send(data);
</script>