// Various formatters.
var formatNumber = d3.format(",d"),
    formatArea = d3.format(",f"),
    formatDate = d3.time.format("%B %d, %Y"),
    formatTime = d3.time.format("%I:%M %p"),
    formatAge = d3.format(".2f");

var currentSet = 0;

var dL = [ 
  { 'entry': 'Imaging Measures',
    'projects': [ 'Project01' ],
    'variables': [ 'Age_At_IMGExam', 'MRI_cort_area.ctx.total', 'MRI_cort_vol.ctx.total', 'MRI_cort_thick.ctx.mean'],
    'names': ['Age', 'Area', 'Volume', 'Thickness'],
    'display': [ 
       function(d) { return formatAge(d.age); },
       function(d) { return formatArea(Math.floor(0.01*d.area*100)/100) + " cm<sup>2</sup>"; },
       function(d) { return Math.floor(0.001*d.vol*100)/100 + " cm<sup>3</sup>"; },
       function(d) { return Math.floor(d.thick*100)/100 + " mm"; }
		]
  },
  { 'entry': 'Cognition Measures',
    'projects': [ 'Project01' ],
    'variables': [ 'Age_At_IMGExam', 'TBX_VOCAB_THETA', 'TBX_flanker_score', 'TBX_ibam_scr'],
    'names': ['Age', 'Vocabulary Score', 'Flanker Score', 'IBAM score'],
    'display': [ 
       function(d) { return formatAge(d.age); },
       function(d) { return d.area; },
       function(d) { return d.vol; },
       function(d) { return d.thick; }
		]
  }  
];
