pipeline {
	 agent any
	 tools {nodejs "node_v10"}
	 stages {
	 stage ('Checkout') {
		steps {
	 git branch:'main', url: 'https://github.com/brandonneo/fireawaylocal.git'
	 }
 }
      stage('Install Typescript') {
        steps {
           sh 'npm install typescript'
            }
         }

 stage('Code Quality Check via SonarQube') {
 steps {
 script {
 def scannerHome = tool 'SonarQube';
 withSonarQubeEnv('SonarQube') {
 sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=OWSAP2 -Dsonar.sources=."
 }
 }
 }
 }
 }
 post {
 always {
 recordIssues enabledForFailure: true, tool: sonarQube()
 }
 }
}
