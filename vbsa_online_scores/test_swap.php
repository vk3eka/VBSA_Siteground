<script>

function dragWord(dragEvent) {
  dragEvent.dataTransfer.setData("text/html", dragEvent.target.textContent + "|" + dragEvent.target.parentNode.id);
}

function dropWord(dropEvent) {
  var dropData = dropEvent.dataTransfer.getData("text/html");
  var dropItems = dropData.split("|");
  var prevElem = document.getElementById(dropItems[1]);
  prevElem.getElementsByTagName("div")[0].textContent = dropEvent.target.textContent;
  dropEvent.target.textContent = dropItems[0];
  dropEvent.preventDefault();
}
</script>

<style>

.right-sidebar .content-area {
  width: 98%;
}

.float-container {
  width: 100%;
  height: 800px;
  display: flex;
  flex-wrap: wrap;
  position: relative;
}

.float-child {
  border: 1px solid black;
  flex: 1 0 14%;
  position: absolute;
  height: 100px;
  width: 100px;
}

</style>

<div class="float-container">
  <div id=1 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:100px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_1.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/1.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/cum-id-fugiunt-re-eadem-defen/">Cum</a></h3>
  </div>

  <!--<br> -->
  <div id=2 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:200px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_2.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/2.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/pauca-mutat-vel-plura-sane-lo/">Pau</a></h3>
  </div>

  <!--<br> -->
  <div id=3 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:300px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_3.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/3.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/respondeat-totidem-verbis/">Res</a></h3>
  </div>

  <!--<br> -->
  <div id=4 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:400px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_4.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/4.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/tum-mihi-piso-quid-ergo/">Tum</a></h3>
  </div>

  <!--<br> -->
  <div id=5 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:500px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_5.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/5.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/neminem-videbis-ita-laudatum/">Nem</a></h3>
  </div>

  <!--<br> -->
  <div id=6 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:600px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_6.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/6.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/sin-laboramus-quis-est-qui-a/">Sin</a></h3>
  </div>

  <!--<br> -->
  <div id=7 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:700px; top:100px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_7.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/7.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/et-harum-quidem-rerum-facilis-est/">Et </a></h3>
  </div>

  <!--<br> -->
  <div id=8 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:100px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_8.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/8.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/nullus-est-igitur-cuiusquam-dies-n/">Nul</a></h3>
  </div>

  <!--<br> -->
  <div id=9 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:200px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_9.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/9.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/huius-lyco-oratione-locuples/">Hui</a></h3>
  </div>

  <!--<br> -->
  <div id=1 0 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:300px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_10.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/10.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/duo-reges-constructio-interre/">Duo</a></h3>
  </div>

  <!--<br> -->
  <div id=1 1 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:400px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_11.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/11.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/quaesita-enim-virtus-est/">Qua</a></h3>
  </div>

  <!--<br> -->
  <div id=1 2 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:500px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_12.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/12.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/idemne-potest-esse-dies-saepius/">Ide</a></h3>
  </div>

  <!--<br> -->
  <div id=1 3 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:600px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_13.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/13.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/hunc-vos-beatum-lorem-ipsum-do/">Hun</a></h3>
  </div>

  <!--<br> -->
  <div id=1 4 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:700px; top:200px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_14.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/14.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/nam-quibus-rebus-efficiunt/">Nam</a></h3>
  </div>

  <!--<br> -->
  <div id=1 5 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:100px; top:300px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_15.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/15.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/nunc-haec-primum-fortasse-audie/">Nun</a></h3>
  </div>

  <!--<br> -->
  <div id=1 6 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:200px; top:300px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_16.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/16.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/me-igitur-ipsum-ames-oporte/">Me </a></h3>
  </div>

  <!--<br> -->
  <div id=1 7 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:300px; top:300px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_17.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/17.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/test1/">tes</a></h3>
  </div>

  <!--<br> -->
  <div id=1 8 class="float-child" ondragover="event.preventDefault()" ondrop="dropWord(event)" style=" left:400px; top:300px">
    <img style="object-fit: cover;width: 100%;height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_18.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/18.png" draggable="true" ondragstart="dragWord(event)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/hello-world/">Hel</a></h3>
  </div>

  <!--<br> -->



  <div id=1 9 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:500px; top:300px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_19.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/19.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/quamquam-id-quidem-licebit-iis/">Qua</a></h3>
  </div>
  <!--<br> -->
  <div id=2 0 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:600px; top:300px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_20.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/20.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/hanc-quoque-iucunditatem-si-vis/">Han</a></h3>
  </div>
  <!--<br> -->
  <div id=2 1 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:700px; top:300px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_21.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/21.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/praetereo-multos-in-bis-doc/">Pra</a></h3>
  </div>
  <!--<br> -->
  <div id=2 2 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:100px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_22.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/22.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/negat-enim-summo-bono-afferre-in/">Neg</a></h3>
  </div>
  <!--<br> -->
  <div id=2 3 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:200px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_23.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/23.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/at-miser-si-in-flagitiosa-et/">At </a></h3>
  </div>
  <!--<br> -->
  <div id=2 4 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:300px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_24.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/24.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/omnes-enim-iucundum-motum-qu/">Omn</a></h3>
  </div>
  <!--<br> -->
  <div id=2 5 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:400px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_25.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/25.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/nam-si-amitti-vita-beata-pote/">Nam</a></h3>
  </div>
  <!--<br> -->
  <div id=2 6 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:500px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_26.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/26.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/transfer-idem-ad-modestiam-vel/">Tra</a></h3>
  </div>
  <!--<br> -->
  <div id=2 7 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:600px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_27.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/27.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/sed-nimis-multa-lorem-ip/">Sed</a></h3>
  </div>
  <!--<br> -->
  <div id=2 8 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:700px; top:400px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_28.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/28.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/quae-fere-omnia-appellantur-uno/">Qua</a></h3>
  </div>
  <!--<br> -->
  <div id=2 9 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:100px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_29.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/29.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/et-ais-si-una-littera-comm/">Et </a></h3>
  </div>
  <!--<br> -->
  <div id=3 0 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:200px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_30.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/30.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/quamquam-in-hac-divisione-rem/">Qua</a></h3>
  </div>
  <!--<br> -->
  <div id=3 1 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:300px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_31.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/31.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/illa-tamen-simplicia-vestra/">Ill</a></h3>
  </div>
  <!--<br> -->
  <div id=3 2 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:400px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_32.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/32.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/nihil-illinc-huc-pervenit/">Nih</a></h3>
  </div>
  <!--<br> -->
  <div id=3 3 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:500px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_33.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/33.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/itaque-ab-his-ordiamur-l/">Ita</a></h3>
  </div>
  <!--<br> -->
  <div id=3 4 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:600px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_34.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/34.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/proizvod2/">pro</a></h3>
  </div>
  <!--<br> -->
  <div id=3 5 class="float-child" ondragover="ondragover(e)" ondrop="ondrop(e)" style=" left:700px; top:500px">
    <img style="object-fit: cover;width: 100%; height: 100%;" src="http://vjezbanje.local/wp-content/themes/storefront/img/pocetna_35.jpg" alt="http://vjezbanje.local/wp-content/themes/storefront/img/35.png" draggable="true" ondragstart="ondragstart(e)">
    <h3 style="display: none;"><a href="http://vjezbanje.local/product/proizvod1/">pro</a></h3>
  </div>
  <!--<br> -->
</div>
