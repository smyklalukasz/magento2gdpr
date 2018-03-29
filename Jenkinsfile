pipeline {
    agent any
    environment {
        ENCRYPTED = credentials('magento2gdpr')
    }
    stages {
        stage('Build') {
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/decrypt.sh && DEPLOY_ENVIRONMENT=Build bin/install.sh && bin/build.sh'
                }
                archiveArtifacts artifacts: 'build/**'
            }
        }
        stage('Test') {
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/test.sh'
                }
                dry pattern: 'build/cpd.xml'
                checkstyle pattern: 'build/checkstyle.xml'
                pmd pattern: 'build/mess.xml'
                junit 'build/test.xml'
                publishHTML([
                    allowMissing: true,
                    alwaysLinkToLastBuild: true,
                    keepAll: false,
                    reportDir: 'build/coverage',
                    reportFiles: 'index.html', 
                    reportName: 'Coverage',
                    reportTitles: ''
                ])
            }
        }
        stage('Deploy') {
            when {
                expression {
                    return env.CHANGE_ID == null
                }
            }
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/deploy.sh Continuous'
                }
            }
        }
    }
    post {
        changed {
            slackSend  channel: '#gdpr', color: ( currentBuild.result != null ? '#d00000' : '#36a64f' ), message: "${env.JOB_NAME} - #${env.BUILD_NUMBER} " + ( currentBuild.result != null ? currentBuild.result : "SUCCESS") + " after (${env.BUILD_URL})"
        }
    }
}
