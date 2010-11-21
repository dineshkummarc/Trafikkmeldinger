TRAFIKKMELDINGER
================

A project I decided to make over a weekend, to show the data concerning traffic
on Norwegian roads on Google Maps.

The source of the data:
<http://www.vegvesen.no/Trafikkinformasjon/Reiseinformasjon/Trafikkmeldinger>
(in Norwegian)

I've put a demo up at: <http://trafikk.bytestork.com/> but it may be updated 
a while after the source code as been updated.

The system is built on top of CakePHP, which doesn't do much other than fetching
the XML data and cache it as JSON for 5 minutes, so the load on the datas 
provider isn't noticable. No databases were harmed in the creation of this site.

I wanted this to be a working example within the weekend I started on it, which
is why it ended up a bit sloppy, but I'll try to get that fixed soon.

Also thanks to [jbueza](<https://github.com/jbueza>) for pointing out my stupid mistakes!