## Configure lost default route in Debian

Recently, my Debian server had no internet connection anymore and I didn't know why.

I was able to ping the default gateway, and it was also configured in /etc/network/interfaces.

But when I did a traceroute to any internet address, I got a "connect: Network is unreachable" immediately, showing that the packets did *not* go to the default gateway.

The command

    /sbin/route

now showed that there was no route to 0.0.0.0 (no default route)

After adding the route (which already was configured in /etc/network/interfaces) with the command

    /sbin/route add default gw 192.168.1.100

...the default route also showed up in /sbin/route, and the internet connection was working again.

I have no clue how my default route got lost! If I find the reason, I will add it to this post.

Edit: We had a power outage due to construction works, and when adding the default route via `route add ..`, it will be lost after a reboot. However, I think it should have been set anyway when configured in /etc/network/interfaces...
