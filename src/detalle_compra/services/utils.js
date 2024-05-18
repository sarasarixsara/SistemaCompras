'use strict';
angular.module('app')
	.factory('Utils', function ( $location, $rootScope, $http, $timeout ) {
		
		function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
				.toString(16)
				.substring(1);
		};
		var	paginate = {
			   current   : '',
			   totalpage : 0,
			   offset    : '',
			   linksCountLimit : 5,
			   recordsPerPage : '',
			   arrlinks   : [],
			   Paginator : function(current,totalpage,recordsPerPage,fuente){
				   this.current   = current;
				   this.totalpage = totalpage;
				   this.recordsPerPage = recordsPerPage;
				   this.getOffset();
				   this.arrlinks = [];
				   this.fuente = fuente;
				   if(this.totalpage==0) { this.totalpage =1; }
				   //this.getLinks('SBRA');
			   },
			   getOffset: function(){				 
				 this.offset = (this.current - 1) * this.recordsPerPage;
			   },
			   getLinks : function(cssclase){
				var output = '';
				var fuente = this.fuente;
				// if the current page is not the first
				if (this.current > 1) {					
					var count = 1;
					for(var j = this.current; j >= 1; j-- ) {
						if (count > this.linksCountLimit){
							break;
							
						};						
						if (j == this.current){							
							continue;
						};
						
						output = "<a href='#page/"+j+"' id='"+j+"' class='"+cssclase+"'>"+j+"</a>\r\n" +output;						
						count ++;
						this.arrlinks.unshift({'url': fuente+'/page/'+j, 'id': j, 'clase': cssclase, 'label': j , 'selec': 0});
						
						
					}
					
					//previous page link
					var prevPage = this.current - 1;
					output = "<a href='#page/"+prevPage+"' id='"+prevPage+"' class='"+cssclase + "'>Anterior</a>\r\n"  +output;
					this.arrlinks.unshift({'url': fuente+'/page/'+prevPage, 'id': prevPage, 'clase': cssclase, 'label': 'Anterior' , 'selec': 0});
					
					if (prevPage > 1){
						// first page link
						output = "<a href='#page/1' id='1' class='" + cssclase + "'>Primero</a>\r\n" + output;
						this.arrlinks.unshift({'url':fuente+'/page/1', 'id': 1, 'clase': cssclase, 'label': 'Primero' , 'selec': 0});
					}	
				}	
					output += "<span class='current'>"+this.current+"</span>\r\n";
					this.arrlinks.push({'url':'', 'id': '', 'clase': 'current', 'label': this.current , 'selec': 1});
					
					// next pages
					count = 1;
					
				for(var i = this.current; i < this.totalpage; i ++) {
					if (count > this.linksCountLimit){
						break;
					}
					if (i == this.current){
						continue;
					}
					output += "<a href='#page/"+i+"' id='"+i+"' class='"+cssclase+"'>"+i+"</a>\r\n";
					count ++;
					this.arrlinks.push({'url': fuente+'/page/'+i, 'id': i, 'clase': cssclase, 'label': i , 'selec': 0});
				}
				
				if (this.current < this.totalpage) {
					// next link
					var next = this.current + 1;
					output += "<a href='#page/"+next+"' id='"+next+"' class='"+cssclase+"'>Siguiente</a>\r\n";
					this.arrlinks.push({'url': fuente+'/page/'+next, 'id': next, 'clase': cssclase, 'label': 'Siguiente' , 'selec': 0});
					
					if (this.totalpage != next){
						// last page link
						output += "<a href='#page/"+this.totalpage+"' id='"+this.totalpage+"' class='"+cssclase+"'>Último</a>\r\n";
						this.arrlinks.push({'url': fuente+'/page/'+this.totalpage, 'id': this.totalpage, 'clase': cssclase, 'label': 'Último' , 'selec': 0});						
					}
					
				}					
				
				//return( output)				  
				return( this.arrlinks )				  
				  }
			   };
		var servicios = {
			goTo: function ( url ) {
				$location.path( '/' + url )
				$rootScope.$$phase || $rootScope.$apply()
			},
			paginate : function(current, totalpage, recordsPerPage, fuente){
				paginate.Paginator(current, totalpage, recordsPerPage, fuente);	
				var result = paginate.getLinks('SBRA2');
				return result;
			},
			newuuid: function() {
				// http://www.ietf.org/rfc/rfc4122.txt
				var s = [];
				var hexDigits = "0123456789abcdef";
				for (var i = 0; i < 36; i++) {
					s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
				}
				s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
				s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
				s[8] = s[13] = s[18] = s[23] = "-";
				return s.join("");
			},
			newguid: function() {
				return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
					s4() + '-' + s4() + s4() + s4();
			},
			hidden_notify: function(){
				$("#infoaction").css("display","block");
				$timeout(function(){
					//$("#infoactiontext").html("");
					$("#infoaction").css("display","none");
				}, 3300 );	
			},
			show_notify: function(){
				/*$("#capainfoaction").css("display","block");*/
				document.getElementById('capainfoaction').style.display='block';
				/*$("#infoaction").css("display","block");*/
				document.getElementById('infoaction').style.display='block';
				$timeout(function(){
					//$("#infoactiontext").html("");
					/*$("#capainfoaction").css("display","none");*/
					document.getElementById('capainfoaction').style.display='none';
					/*$("#infoaction").css("display","none");*/
					document.getElementById('infoaction').style.display='none';
				}, 1500 );	
			}			
		}
		return servicios
	})