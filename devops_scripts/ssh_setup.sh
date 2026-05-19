mkdir -p ~/.ssh
echo "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIF5dvd77ezk99buwynA3XHNyB8TR/MtAo7RH9OhSlA1+ hpi9@RonySantraNTT" >> ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
restorecon -Rv ~/.ssh
echo "SSH KEY SETUP COMPLETE"
