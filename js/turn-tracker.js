// JavaScript Document

function mode(array){
	//console.log("Running mode...");
		
	if(array.length === 0)
	{return null;}
		
	var modeMap = {};
	var maxEl = array[0], maxCount = 1;

	for(var i = 0; i < array.length; i++){
		var el = array[i];
		if(modeMap[el] === null)
		{modeMap[el] = 1;}
		else
		{modeMap[el]++;}

		if(modeMap[el] > maxCount)
		{
			maxEl = el;
			maxCount = modeMap[el];
		}
	}
	return maxEl;
}


function getCurrentTurns(passmediacode, season, widget_num, currentday = 'null'){
		
		//console.log("Running getTurns..." + passmediacode + "|" + season + "|" + currentday);
		
		jQuery.ajax({
			url: "wp-content/plugins/mtbachelor-turn-tracker/get-stats.php?v=2",
			data: jQuery.param( {passmediacode: passmediacode, season : season, currentday : currentday, widget_num : widget_num}) ,
			dataType: 'html',
			type: "GET",
			success: function(data) {
			 
				//console.log(data);

				var outerTableHTML = jQuery( data ).filter('center').first().html();
				//console.log(outerTableHTML);
				
				var innerTableHTML = "";
				var tableString = "";

				if(currentday === 'null'){
					innerTableHTML = jQuery(outerTableHTML).find("tr td b:contains('Available Dates')").closest('tbody').html();
				}else{
					innerTableHTML = jQuery(outerTableHTML).find("tr td b:contains('Date & Time')").closest('tbody').html();
				}
				
				//console.log(innerTableHTML);
				
				//$("#currentday").append('<table>' + innerTableHTML + '</table>');
				
				var rowArray = jQuery(innerTableHTML).slice(1).map(function(i,el) {
					var tds = jQuery(el).find("td");
					return { "date" : tds.eq(0).html(), "turns" : tds.eq(1).text(), "vertical_ft" : tds.eq(2).text(), "vertical_m" : tds.eq(3).text() };
				}).get();
				
				if(currentday === 'null'){
					tableString += '<table><thead><tr><th></th><th>Dates</th><th>Turns</th><th>Vert (ft)</th><th>Vert (m)</th></tr></thead><tbody>';
				}else{
					tableString += '<table><thead><tr><th></th><th>Date/Time</th><th>Chair</th><th>Vert (ft)</th><th>Vert (m)</th></tr></thead><tbody>';
				}
				
				var chairs = [];
					
				jQuery.each(rowArray, function( key, value) {
					if(value.date !== undefined && value.date.indexOf('TOTAL')<1 && value.date.indexOf('AVERAGE')<1){
						tableString += '<tr><td class="mt-icon"><img src="/wp-content/plugins/mtbachelor-turn-tracker/images/mtbachelor-turn-tracker-fav.png" width="40%"></td><td class="turnDate">' + value.date + '</td><td>' + value.turns + '</td><td>' + value.vertical_ft + '</td><td>' + value.vertical_m + '</td></tr>';
						if(currentday !== 'null'){chairs.push(value.turns); }
					}
					
				});
				
				tableString +='</tbody><tfoot>';
				
				jQuery.each(rowArray, function( key, value) {
					if(value.date !== undefined && value.date.indexOf('TOTAL')>0){
						tableString += '<tr><td colspan="2">' + value.date + '</td><td><b>' + value.turns + '</b></td><td><b>' + value.vertical_ft + '</b></td><td><b>' + value.vertical_m + '</b></td></tr>';
					}
					
				});
				
				jQuery.each(rowArray, function( key, value) {
					if(value.date !== undefined && value.date.indexOf('AVERAGE')>0){
						
						if(currentday === 'null'){
							tableString += '<tr><td colspan="2">' + value.date + '</td><td><b>' + value.turns + '</b></td><td><b>' + value.vertical_ft + '</b></td><td><b>' + value.vertical_m + '</b></td></tr>';
						}else{
							var modeChairs = mode(chairs);
							tableString += '<tr><td colspan="2">' + value.date + '</td><td><b>' + modeChairs + '</b></td><td><b>' + value.turns + '</b></td><td><b>' + value.vertical_ft + '</b></td></tr>';
						}
					}
					
				});
				
				tableString += '</tfoot></table>';
				
				//console.log(tableString);
				
				if(currentday === 'null'){
					jQuery('#mtbachelor-turn-tracker-' + widget_num).html(tableString);
					//console.log("Writing stats to " + widget_num);
					//console.log("Passmediacode: " + passmediacode);
					//console.log("Season: " + season);
				}else{
					jQuery('#currentday-' + widget_num + ' #inner-' + widget_num).html(tableString);
				}
				
				jQuery('#mtbachelor-turn-tracker-' + widget_num + ' .turnDate a').addClass('turnDateLink-' + widget_num).attr('data-fancybox', 'turns').attr('data-src', '#currentday-' + widget_num).attr('href', 'javascript:;').attr('data-querystring', jQuery('.turnDate a').attr('href'));
				//.attr('data-type', 'ajax').attr('data-src', $('.turnDate a').attr('href')).attr('href', 'javascript:;');
				
				var count = 0;	
				jQuery("a.turnDateLink-" + widget_num).each(function() {
					jQuery(this).addClass('turnDateLink-' + widget_num + '-' + count);
					jQuery(this).attr('data-link-id', widget_num + '-' + count);
					jQuery(this).attr('id', 'turnDateLink-' + widget_num + '-' + count);
					count ++;
				});
										
				jQuery('#mtbachelor-turn-tracker-' + widget_num + ' td').not(".turnDate, .mt-icon").html(function(index,html){
					return html.replace('turns','').replace('ft','').replace('m','');
				});
					
					
				jQuery('#currentday-' + widget_num + ' #inner-' + widget_num + ' td').not(".turnDate, .mt-icon").html(function(index,html){
					return html.replace('ft','').replace('m','');
				});

     		}
		
		
		});
		
	}