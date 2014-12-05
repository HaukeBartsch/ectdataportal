var genes = new Array();
var snps = new Array();
var masterlist = snps;
//var ar_snps = new Object();
var ar_chromosome = new Object();
var loading = false;
var count = 0;
var chunk = 20; // needs to be more than the number of lines on the page
var range = 100;

// requires to have version and project_name defined

jQuery(document).ready(function() {
  jQuery('.current-project').text(project_name);
  jQuery.get('/data/'+project_name+'/data_uncorrected'+version+'/SNPs/'+project_name+'_SNPs.txt', function(data) {
    data = data.split(/\n/);
    jQuery('#num-total').text(formatNumber(data.length));
    data.forEach(function(d,i) {
       var a = d.split(",");
       if (a.length != 5) {
          console.log('wrong entry for: ' + a.join(" "));		
	  return;
       }
       snps.push(a);
       //if (typeof ar_snps[a[0]] === "undefined")
       // ar_snps[a[0]] = 1;
       if (typeof ar_chromosome[a[1]] === "undefined")
	 ar_chromosome[a[1]] = 1;
    });
    masterlist = snps;
    display();
    jQuery(window).sausage();
    search();
    //for (var i = 0; i < 3; i++)
    //  addPage(i);
    //
  }).fail(function() {
    alert('Error: The SNP data for this project could not be found.');
  });  
		 
  jQuery(window).scroll(function() {
      if (loading)
 	return;
   if ($(window).scrollTop() > $(document).height() - $(window).height() - 200) {
     loading = true;
     setTimeout(function(){
       addPage(count+1);
       jQuery(window).sausage('draw');
       loading = false;
     }, 250);
   }
  });
			 
  jQuery('#snp-name').change(function(){
     search();
  });
  jQuery('#chromosome').change(function(){
     search();
  });
  jQuery('#basepair').change(function(){
     search();
  });
  jQuery('#gene-name').change(function(){
     searchGene();
  });
			 	
  jQuery('.page-set').on("click", ".add-to-download", function(event) {
     var snp = jQuery(this).attr('value');
     var txt = jQuery('#list-of-snps').val();
     var found = false;
     var alreadyOn = jQuery(this).hasClass('btn-primary');
     if (alreadyOn) {
       jQuery(this).removeClass('btn-primary');
       found = true;
     }
     lines = txt.split("\n");
     var alreadyPresent = false;
     jQuery(lines).each(function(i,d) {
	if (d == snp) {	  
	  alreadyPresent = true;
	  found = false; // keep false
          return false;
	}
     });
     if (alreadyPresent && alreadyOn) {
       // remove from list
       found = true; // don't add again
       var str = "";
       jQuery(lines).each(function(i,d) {
	  var entry = d.trim();
  	  if (entry.length > 0 && entry != snp) {
	    str = str + d + "\n";
	  }
       });
       jQuery('#list-of-snps').val(str);        
     }
     if (!found) {
       txt = txt + snp + "\n";
       jQuery('#list-of-snps').val(txt);
       jQuery(this).addClass('btn-primary');
     }
  });
			 
  jQuery('#download-now').click(function() {
     var str = encodeURI(jQuery('#list-of-snps').val());				 
     if (str == "")
       return; // nothing to do
     var btn = jQuery('#download-now');
     btn.button('loading');
     jQuery('#showResults').modal('show');
				  
     jQuery.get('download.php?_v=&project_name='+project_name+'&snps='+str, function(data) {
        // strip the first line
        if (data.split("\n").length > 1) {
	  tmp = data.split("\n");
	  firstLine = tmp.splice(0,1);
	  jQuery('#result-text-area-info').text(firstLine[0]);
	  data = tmp.join('\n');
	}

        // instead of downloading the file (no file name can be given), lets display the file in a dialog
	jQuery('#result-text-area').val(data);
		  
        // download data.file now
        /* document.location = 'data:Application/octet-stream,' + 
			encodeURIComponent(data); */
	btn.button('reset');
     });	       
  });
  jQuery('#showResults').on('hidden', function() {
     jQuery('#result-text-area').val('wait for it...');			    
  });
  setTimeout(function() { readUCSCGenes(); }, 500);
});

function switchRange(value) {
  range = value;
  search();
}

function searchGene() {
  var gene = jQuery('#gene-name').val();
  if (gene == "")
    return;
  
  var reg = new RegExp(gene);
  jQuery.each(genes, function(i,d) {
     if (reg.test(d.name)==true || reg.test(d.unique_name)==true) {
        jQuery('#basepair').val(d.a+"-"+d.o);
	jQuery('#chromosome').val(d.chromosome);
        jQuery('#gene-name').val(d.name);
        jQuery('#gene-name').attr('title', d.description);
        search();
        return false;        
     }
  });
}

function search() {
  var sn = jQuery('#snp-name').val();
  var ch = jQuery('#chromosome').val();
  var bp = jQuery('#basepair').val();
  jQuery('.page-set').children().remove();
  jQuery('#num-current').html("<img src=\"img/ajax-loader.gif\" alt=\"loading...\"></img>");
  
  setTimeout( function() {
    // copy masterlist over adhere to search
    snps = new Array();
    var reg  = new RegExp(sn);
    var reg2 = new RegExp(ch);

    masterlist.forEach(function(d,i) {
      if (i == 0) {
  	// header add always
        snps.push(d);
	return;
      }
      if (sn != "") {
        if (reg.test(d[0])==false)
           return;
      }
      if (ch != "") {
	if (reg2.test(d[1])==false)
	  return;
      }
      if (bp != "") {
        if (bp.split("-").length == 2) { // range of bp values
          a = parseInt(bp.split("-")[0].trim());
          b = parseInt(bp.split("-")[1].trim());
          if (d[2] < (a-range/2) || d[2] > (b+range/2))
	    return;    
	} else {
          var tmp = parseInt(bp);
   	  if (d[2] < (tmp-range/2) || d[2] > (tmp+range/2))
	    return;
	}
      }
      snps.push(d);
    });
    jQuery('#num-current').text(formatNumber(snps.length-1));
    for (var i = 0; i < 5; i++)
       addPage(i);
    jQuery(window).sausage('draw');
  },100);
}

function formatNumber(val) {
  var num = val+"";
 
  var erg = "";
  for (var i = num.length-1; i>=0; i--) {
    if ((num.length-i)>2 &&  ((num.length-i-1) % 3) == 0)
      erg=","+erg;
    erg=num.charAt(i)+erg;
  }
  
  return erg;
}

function addPage(c) {
  count = c;
  var vals = snps.splice(count*chunk,chunk);
  var page = '<ol class="page">';
  jQuery(vals).each(function(i,d) {
    page = page + '<li class=\"table-row\">';
    if (c==0 && i==0) {
      page = page + '<span class="badge column-s">#</span>';
      page = page + '<span class="column-s">Export</span>';
    } else {      
      page = page + '<span class="badge column-s">'+(i+count*20)+'</span>';
      page = page + '<span class="column-s"><button class="btn add-to-download" value="'+d[0]+'"></button></span>';
    }
    if (c==0 && i==0)
      page = page + '<span class="column">'+d[0]+'</span>';
    else {
      // http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=165810
      if (d[0].substr(0,2) == "rs")
        page = page + '<span class="column"><a href="http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=' + d[0].substr(2) + '">'+d[0]+'</a></span>';	
      else
        page = page + '<span class="column"><a href="http://www.ncbi.nlm.nih.gov/snp/?term='
	       + d[0] + '">'+d[0]+'</a></span>';
    }
    if (c==0 && i==0) {
      page = page + '<span class="column-s"> '+d[1]+'</span><span class="column">'+d[2]+'</span>';
      page = page + '<span class="column-s">'+d[3]+', '+d[4]+'</span></li>';
    } else {
      page = page + '<span class="column-s"> '+d[1]+'</span><span class="column">'+formatNumber(d[2])+'</span>';
      page = page + '<span class="column-s">'+d[3]+d[4]+'</span></li>';
    }
  });
  page = page + '</ol>';
  jQuery('.page-set').append(page);
}

function display() {
  // jQuery('#snp-name').autocomplete({ source: Object.keys(ar_snps) });
  jQuery('#chromosome').autocomplete({ source: Object.keys(ar_chromosome) });
}

function readUCSCGenes() {  
  genes = [];
  console.log('start reading genes files...');
  jQuery.getJSON('/data/'+project_name+'/data_uncorrected'+version+'/SNPs/'+project_name+'_gene_annotate.json', function(data) {
    var items = [];
    
    var geneNameIndex = jQuery.inArray("#hg19.knownGene.name", data.header);
    var chromIndex    = jQuery.inArray("hg19.knownGene.chrom", data.header);
    var startIndex    = jQuery.inArray("hg19.knownGene.txStart", data.header);
    var endIndex      = jQuery.inArray("hg19.knownGene.txEnd", data.header);
    var symbolIndex   = jQuery.inArray("hg19.kgXref.geneSymbol", data.header);
    var descIndex     = jQuery.inArray("hg19.kgXref.description", data.header);
    reg = new RegExp(/chr([0-9]+)/);
    reg2 = new RegExp(/[^_]+([0-9]+)/);
    
    jQuery.each(data.data, function(key, val) {
       var chr = reg.exec(val[chromIndex]);
       var hei = reg2.exec(val[geneNameIndex]);
       var chrom = -1;

       if (val[chromIndex].charAt(3) == 'X') {
          chrom = 23;
       }
       if (val[chromIndex].charAt(3) == 'Y') {
          chrom = 24;
       }

       if (chrom == -1 && chr != null && chr.length > 1) {
          if (chrom == -1)
             chrom = parseInt(chr[1]);
       }
       if (chrom > -1) {
         genes.push({ name: val[symbolIndex],
              unique_name: val[geneNameIndex],
              a: parseInt(val[startIndex]),
              chromosome: chrom,
              o: parseInt(val[endIndex]),
              description: '"'+val[descIndex]+'"'
         });
       } else {
	 console.log('something went wrong with this chromosome: ' + chrom);
	 
       }
    });
    console.log('finished reading gene file...');
  });
}

function downloadSNPs() {
  var snsp = jQuery('#list-of-snps').val();
  
  /*  setTimeout(function () {
	   btn.button('reset')
      }, 3000) )  */
  
  if (snsp == "") {
     return; // do nothing list is empty  
  }
}
