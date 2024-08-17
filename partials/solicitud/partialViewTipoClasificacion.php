							 
						    <!-- inicio boton combo de tipo de orden de compra -->		
								 <select name="TipoCompra_multiple" 
									     id="TipoCompra_multiple" 
										 class="chzn-select" 
										 onchange="FMD_TipoCompraMultiple('multiple',this.value)">				
										<option value="">-Tipo-</option>
									         <?php
													
													foreach($estadosTipoOrdenCompra as $indice => $registro)
													{
														?>
														<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
														<?php
													}
											   ?>
								</select>
							<!-- fin -->
						
							<!-- inicio boton combo Classificacion de compra -->		
							    <select name="TipoClasCompra_multiple" 
									     id="TipoClasCompra_multiple" 
										 class="chzn-select" 
										 onchange="FMD_TipoClasCompraMultiple('multiple',this.value)">				
										<option value="">-Clasificaci&oacute;n-</option>
									         <?php
													
													
													foreach($tiposCompra as $indice => $registro)
													{
														?>
														<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
														<?php
													}
											   ?>
								</select>