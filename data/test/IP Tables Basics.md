## IP Tables Basics

Here's a short reference on the basic configuration of an IP Tables firewall on Debian Linux.

This assumes basic knowledge in firewalls. As with access lists in general, the order of the statements is important and IP Tables processes them from top to bottom.

If unsure with the commands and if you only have access to the server over network, use a method to avoid locking you out of the system like described in an [earlier post][].  
 

List existing rules:

    iptables -L (-v)

Allow established connections (stateful firewall operation): <!--more-->

    iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
    (if the above doesn't work:)
    iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

Allow SSH from specific sources and HTTP from everywhere:

    iptables -A INPUT -s 70.80.90.100 -p tcp --dport ssh -j ACCEPT
    iptables -A INPUT -s 80.90.100.0/23 -p tcp --dport ssh -j ACCEPT
    iptables -A INPUT -p tcp --dport 80 -j ACCEPT

Block everything:

    iptables -A INPUT -j DROP

Insert rule on the first line to accept traffic on loopback interface:

    iptables -I INPUT 1 -i lo -j ACCEPT

Save IP Tables to keep the configuration at reboot:

    iptables-save

 

  [earlier post]: http://kb.haeringer.org/define-commands-for-later-execution-auto-restore-backup-configurations/
    "Define commands for later execution / auto-restore backup configurations"
