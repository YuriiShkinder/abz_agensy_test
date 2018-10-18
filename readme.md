# Abz.agensy test
Install docker and docker-compose
- sudo apt-get update
- sudo apt-get install -y software-properties-common && apt-get install -y apt-transport-https
- sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add -
- sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
- sudo apt-get update
- sudo apt-cache policy docker-ce
- sudo apt-get install -y docker-ce
- sudo apt-get install -y python-pip && pip install -I docker-compose==1.16.1
- sudo usermod -aG docker $(whoami)
- sudo service docker restart or sudo systemctl restart docker

# Running  project
- ./run.sh
- http://127.0.0.1:8080