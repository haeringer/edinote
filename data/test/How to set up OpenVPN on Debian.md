This is how to set up OpenVPN to connect from a remote Windows client to a Debian server.  
 

**Download & install OpenVPN on the Debian server**

    apt-get install openvpn

Create two subdirectories "keys":

    mkdir /etc/openvpn/keys
    mkdir /usr/share/doc/openvpn/examples/easy-rsa/2.0/keys

The program EasyRSA was installed together with OpenVPN. We use it to...  
 

**Generate the necessary keys and certificates**

Open the vars file in /usr/share/doc/openvpn/examples/easy-rsa/2.0/ with a text editor and replace the following sample data at the end of the file with your data:

    vi vars

    set KEY_COUNTRY=US
    set KEY_PROVINCE=CA
    set KEY_CITY=SanFrancisco
    set KEY_ORG=OpenVPN
    set KEY_EMAIL=mail@host.domain

Save the file, source it and build CA key and certificate (CA = certification authority). You will be asked to enter some information, but as you will see, most of the fields already contain the data you have entered into the vars file.

    source vars
    ./clean-all
    ./build-ca

Now create key & certificate for the server, going through a similar procedure and confirming the questions at the end with y:

    ./build-key-server EXAMPLESERV01

create key & certificate for the client:

    ./build-key EXAMPLECLNT01

and create the Diffie Hellmann parameters with

    ./build-dh

Copy the key, crt and pem files to the OpenVPN subdirectory keys:

    cp *.key *.crt *.pem /etc/openvpn/keys

 

**Edit the configuration file**

In /usr/share/doc/openvpn/examples/sample-config-files you can find a set of sample configuration files ;) Copy server.conf.gz to the openvpn configs directory, unzip it and open it for editing:

    cp server.conf.gz /etc/openvpn
    gunzip server.conf.gz
    vi server.conf

You can leave all parameters as they are, except for the paths to the files we just created and the IP address of your home net:

    ca /etc/openvpn/keys/ca.crt
    cert /etc/openvpn/keys/EXAMPLESERV01.crt
    key /etc/openvpn/keys/EXAMPLESERV01.key
    dh /etc/openvpn/keys/dh1024.pem
    push "route 192.168.2.0 255.255.255.0"

The push parameter is needed for routing packets from the OpenVPN network 10.8.0.0/24 (default IP) to our home network. For making this possible, we also need to enable IP forwarding in Debian (stays enabled only until the next reboot):

    echo "1" > /proc/sys/net/ipv4/ip_forward

To execute this automatically after reboot, add the following line in your network interface config file as an additional line to eth0:

    vi /etc/network/interfaces

    post-up echo 1 > /proc/sys/net/ipv4/ip_forward

 

**Download & install OpenVPN on the Windows client**

[Download][] and install the OpenVPN client software.

Create a "keys" directory (C:Program FilesOpenVPNeasy-rsakeys), and copy the following files from the Debian server into it:  
/etc/openvpn/keys/ca.crt  
/etc/openvpn/keys/EXAMPLECLNT01.crt  
/etc/openvpn/keys/EXAMPLECLNT01.key

Copy the sample config file client.ovpn from C:Program FilesOpenVPNsample-config to ..OpenVPNconfig, and open it with a text editor. Again, you can leave all parameters as they are, except for the
paths to the files we just copied, and your domain name:

    remote example.com 1194
    ca "C:/Program Files/OpenVPN/easy-rsa/keys/ca.crt"
    cert "C:/Program Files/OpenVPN/easy-rsa/keys/EXAMPLECLNT01.crt"
    key "C:/Program Files/OpenVPN/easy-rsa/keys/EXAMPLECLNT01.key"

 

**Configure the router**

Configure your router for port forwarding (in your router configuration interface, look somewhere under NAT settings). Make an entry defining that UDP requests at port 1194 get forwarded to the IP address of your Debian server.

 

**Start OpenVPN**

For testing purposes, start the OpenVPN server with

    openvpn --config /etc/openvpn/server.conf

and try connect your client. If the connection doesn't work, have a look at the next section "troubleshooting". If the connection works, you can stop OpenVPN with Ctrl-C and start it as a daemon (a background service):

    openvpn --daemon --config /etc/openvpn/server.conf

 

**Troubleshooting**

If the OpenVPN server doesn't start, check if you have entered all the paths in the server.conf correctly.

If the OpenVPN Windows client has problems finding the files, use a simple path like C:openvpn-keys, instead of the program files directory. Also check the paths in the config file.

When your server is running and you start the client, but there aren't any messages at the server saying there's a client with your IP trying to connect, then your client is not able to reach the server yet. Check the port forwarding settings on your router and test your connections with pings.

If there are messages at your server, saying there's a client with your IP trying to connect, but the VPN connection doesn't work, try to change from UDP to TCP. You have to make the change at three places: In the server.conf, the client.ovpn and at the port forwarding setting on the router. In the config files, just uncomment `proto tcp` / comment `proto udp`.

If the connection was established successfully but you can't reach any devices within your home network, change the tunnel mode by uncommenting `dev tap0` in the server.conf and commenting `dev tun`.

To restart a running instance of the OpenVPN server (e.g. after having changed the configuration), use

    /etc/init.d/openvpn restart

  [Download]: http://openvpn.net/index.php/open-source/downloads.html
