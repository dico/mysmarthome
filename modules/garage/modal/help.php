<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Using Telldus to control your garage doors</h4>
</div>


<div class="modal-body">

	<h4 style="font-weight:bold;">Motor</h4>
	<p>To control your garagedoors, you will need a wireless receiver to work as a remote push-button for your motor.</p>
	<p>I recommend RX-Multi. Se example:</p>
	<ul>
		<li>
			<a href="http://www.chinaremote.com/Products/Receivers/">http://www.chinaremote.com/Products/Receivers/</a>
		</li>
		<li>
			<a href="http://portspesialisten.com/fjernkontroll/mottaker-433mhz/">http://portspesialisten.com/fjernkontroll/mottaker-433mhz/</a>
		</li>
	</ul>

	<p>See these links for more information about using and help with the RX-Multi:</p>
	<ul>
		<li>
			<a href="http://www.telldus.com/forum/viewtopic.php?f=11&t=3893">http://www.telldus.com/forum/viewtopic.php?f=11&t=3893</a>
		</li>
		<li>
			<a href="http://robertan.com/home/2015/05/10/open-garage-port-with-telldus/">http://robertan.com/home/2015/05/10/open-garage-port-with-telldus/</a>
		</li>
	</ul>

	<h4 style="font-weight:bold;">Motor-setup in Telldus Live</h4>
	<p>Se also links above.</p>
	<ol>
		<li>Pair the receiver in Telldus Live!</li>
		<li>Wait for it to sync into MSH or sync manually</li>
		<li>Go to the Devicemanager in MSH, and add Magnet switch to the Garage door category.</li>
		<li>After category is added, it will show up in the dropdown-list when adding a new garage door</li>
	</ol>


	<h4 style="font-weight:bold;">Status-setup in Telldus Live</h4>
	<p>I'm using <a href="http://www.nexa.se/LMST606.htm" target="_blank">Nexa Magnet switches</a> for this.</p>

	<ol>
		<li>Pair the Magnet switch in Telldus Live!</li>
		<li>Wait for it to sync into MSH or sync manually</li>
		<li>Go to the Devicemanager in MSH, and add Magnet switch to the Garage door category.</li>
		<li>After category is added, it will show up in the dropdown-list when adding a new garage door</li>
	</ol>

	<p><b>The device status can be updated in one or two ways.</b></p>

	<b>First way</b><br />
	<p>Add monitoring for the Magnet swtich device. The downside is that you have to wait for the next sync to get the garage door status.</p>

	<b>Second way</b><br />
	<p>If you have a Telldus Pro subscription, you can create a URL-trigger in the Event-page.
		You have to create two URL-triggers, one for open and another for closed. This will auto-update the device value instantly.</p>
	<p>Go to the settings page in MSH to create URL-trigger. After you have created an URL, just paste this in to the Event in Telldus Live.</p>


</div> <!-- end modal-body -->


<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
</div>
