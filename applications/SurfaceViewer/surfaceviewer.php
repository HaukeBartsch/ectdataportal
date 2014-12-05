        <canvas id="colormapDisplay" width="200" height="60" style="display: none;"></canvas>
        <div id="controlIcon" onclick="jQuery('#dialog').dialog( 'open' ); jQuery('#dialog').dialog('option', 'width', 480); jQuery('#dialog').dialog('option', 'height', 300);" style="font-size: 30pt;">C</div>
        <div id="message-text" style="font-size: 12pt;">loading ...</div>
        <div id="dialog" title="Controls" style="display: none; width: 650px;">
           <div>
              <span style="margin: 2px;">Display: </span>
              <span id="rate-of-change-box" style="margin-bottom: 2px; display: none; float: right;">
                    <input type="checkbox" id="rate-of-change"/><label class="checkbox inline" for="rate-of-change">rate of change</label>
              </span>
              <select id="modality"> <!-- add to the name -lh.bin.json -->
		    <option fname="unknown" index="1" title="Some measure">Predicted values</option>
		    <option fname="unknown" index="0" title="Some other measure main effect">Main effect</option>
		    <option fname="unknown" index="1" title="Some other measure grouping effect">Grouping effect</option>
		    <option fname="unknown" index="2" title="Some other measure interaction effect">Interaction effect</option>
              </select>
           </div>
           <div id="controls" style="margin-bottom: 5px;">
             <button class="btn" onclick="toggleRHMesh();" id="toggle-right-hemisphere">Hide Right</button>
	     <button class="btn" onclick="toggleLHMesh();" id="toggle-left-hemisphere">Hide Left</button>
             <button class="btn" onclick="showHemisphere('lh','lh','#ll-button');" id="ll-button" class="hemi-buttons" title="left lateral view"><small>LL</small></button>
             <button class="btn" onclick="showHemisphere('rh','rh','#rl-button');" id="rl-button" class="hemi-buttons" title="right lateral view"><small>RL</small></button>
             <button class="btn" onclick="showHemisphere('lh','rh','#lm-button');" id="lm-button" class="hemi-buttons" title="left medial view"><small>LM</small></button>
             <button class="btn" onclick="showHemisphere('rh','lh','#rm-button');" id="rm-button" class="hemi-buttons" title="right medial view"><small>RM</small></button>
	     <span style="float: right; display: none;" id="controls-groups-block">group: <span id="controls-groups" style="display: none;"></span> </span>
	   </div>
        <div>
        <div id="colormapDialog" title="Colormap Editor" style="display: none; width: 650px; height: 300px;">
           <!-- draw the colormap and the linear function -->
           <canvas id="colormapDialogDraw" style="height: auto; width: 100%;"></canvas>
           <!-- show the values in the interface -->
	     <div>&nbsp;</div>
	   <div>
	     <input type="text" id="cm-min-value" value="-5" style="width: 20%; text-align: right;display: inline-block;"></input>
             <input type="text" id="cm-min-mid-value" value="0" style="width: 20%;text-align: right;display: inline-block;"></input>
             <input type="text" id="cm-max-value" value="5" style="width: 20%;text-align: right;display: inline-block;float: right;"></input>
             <input type="text" id="cm-max-mid-value" value="0" style="width: 20%;text-align: right;display: inline-block;float: right;margin-right: 5px;"></input>
             <input type="hidden" id="cm-min-value-opa" value="1"></input>
	     <input type="hidden" id="cm-min-mid-value-opa" value="0.5"></input>
	     <input type="hidden" id="cm-max-value-opa" value="1"></input>
	     <input type="hidden" id="cm-max-mid-value-opa" value="0.5"></input>
           </div>
        </div>
        <!--<input type="text" id="amount" style="float: right; border:0; color:#f6931f; font-weight:bold; margin-bottom: 5px;" /> -->
       <div style="margin-top: 10px; height: 15px;"><div style="font-size: 1.1em; float: left;">Color:</div><div style="float: right; margin-top: -5px;">bg:<input id="background-color-picker" class="minicolors" type="text" value="#000000"></div></div>
        <br/>
        <div>
          <div id="Color" style="display: inline-block; width: 60%;"></div>
          <input type="text" id="color-min-value" style="float: left; width: 15%; border: 0; margin-right: 10px; color: #f6931f; font-weight: normal;" />
          <input type="text" id="color-max-value" style="float: right; width: 15%; border: 0; color: #f6931f; font-weight: normal;" />
        </div>
        <div id="Options">
           <input type="checkbox" id="options-fdr"/><label class="checkbox inline" for="options-fdr" title="Warning: this is a work in progress... -log10() of false discovery rate threshold for level of .05."><small>Apply FDR&lt;.05 treshold (<span class="fdr-value"></span>)</small></label>
        </div>
      </div>
      <br/>
      <div style="margin-top: 15px;">
        <div id="inflate" style="float: right; margin-bottom: 15px; width: 80%"></div>
        Inflate:
      </div>
      <br/>
      <div style="display: block; width: 100%;">
        <span>Predictor (<span class="edit" id="age-value"></span>):</span>
          <span style="float: right;"><input id="geometry-enabled" type="checkbox"></input><label class="checkbox inline" for="geometry-enabled">adjust geometry</label></span>
          <span id="age" class="ui-slider ui-btn-down ui-btn-corner-all" role="application" style="display: inline-block; width: 80%;"></span>
          <button id="animate-predictor" label="play" style="float: right; margin-top:-4px;"/>play</button>
      </div>
    </div>

 
	<!-- <script src="js/Three.js"></script> -->

</body>
</html>
