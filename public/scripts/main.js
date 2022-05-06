jQuery(function ($) {
  /* guarda la seccio en cookie si es categoria o buscar*/
  //catala
  var hrefCa = $(".lang-item-ca a").attr("href");
  var array_hrefCa = hrefCa.split("/");
  //espanyol
  var hrefEs = $(".lang-item-es a").attr("href");
  var array_hrefEs = hrefEs.split("/");

  //quan estic en categoria
  if (array_hrefCa[3] === "product-category") {
    // guarda la section
    document.cookie = "section=product-category;path=/";

    /* guarda la categoria en cookie en els 2 idiomes per al siguiente/anterior dels productes*/
    // catala
    let posC = array_hrefCa.length - 2;
    if (array_hrefCa[posC]) {
      document.cookie = "cat-ca=" + array_hrefCa[posC] + ";path=/";
    }
    // Espanyol
    let posE = array_hrefEs.length - 2;
    if (array_hrefEs[posE]) {
      document.cookie = "cat-es=" + array_hrefEs[posE] + ";path=/";
    }
    //quan estic en buscar
  } else if (
    array_hrefCa[3].split("=")[0] === "?s" ||
    array_hrefEs[4].split("=")[0] == "?s"
  ) {
    let url = location.href;
    let inicio = url.indexOf("=") + 1;
    let fin = url.indexOf("&");
    let text_to_search = url.substring(inicio, fin);
    /* guarda la section que estic i la cadena de text a buscar*/
    document.cookie = "section=search;path=/";
    document.cookie = "search=" + text_to_search + ";path=/";
    //si no es ni buscar ni categoria posa a null la cookie section
  } else if (array_hrefCa[3] !== "product") {
    document.cookie = "section=null ;path=/";
  }
});
