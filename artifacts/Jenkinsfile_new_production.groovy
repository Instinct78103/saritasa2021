def config = [
  repo: 'git@gitlab.saritasa.com:saritasa.com/saritasa2019.git',
  branch: "${env.GIT_BRANCH}",
  server: '172.31.254.111',
  user: 'deploy',
  location: '/var/www/new.saritasa.com/',
  cdnbucket: 's3-cdn.new.saritasa.com'
]

node {
  stage('scm') {
    checkout(scm)
  }
  stage('ansistrano-deploy') {
    env.ANSIBLE_HOST_KEY_CHECKING='False'
    env.ANSIBLE_REMOTE_USER=config.user
    env.ANSIBLE_SSH_ARGS='-C -o ControlMaster=auto -o ControlPersist=60s -o ForwardAgent=yes'
    sshagent(['saritasa-com-prod']) {
        sh(script: "ansible-playbook -i ${config.server}, \
        artifacts/ansistrano-deploy.yml \
        --extra-vars \
        'deploy_path=${config.location} deploy_repo=${config.repo} deploy_branch=${config.branch} s3bucket=${config.cdnbucket}'")
    }
  }
}
