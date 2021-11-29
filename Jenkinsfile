pipeline {
	 agent any
	
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
 sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=OWSAP -Dsonar.sources=."
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
