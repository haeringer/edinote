## Find out the primary nameserver for a domain

To find out the primary nameserver for a specific domain, there's a very handy command in nslookup: `set querytype=soa`

Example on Windows cmd - look for the "primary name server" output line (on Linux it's called "origin"):

    C:\ben>nslookup
    Standardserver:  nameserv.example.local
    Address:  10.11.12.13

    > set querytype=soa
    > haeringer.org
    Server:  nameserv.example.local
    Address:  10.11.12.13

    Nicht autorisierte Antwort:
    haeringer.org
            primary name server = ns1.hans.hosteurope.de
            responsible mail addr = hostmaster.haeringer.org
            serial  = 2012070520
            refresh = 16384 (4 hours 33 mins 4 secs)
            retry   = 2048 (34 mins 8 secs)
            expire  = 1048576 (12 days 3 hours 16 mins 16 secs)
            default TTL = 2560 (42 mins 40 secs)
    >
